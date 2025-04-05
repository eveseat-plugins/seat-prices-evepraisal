<?php

namespace RecursiveTree\Seat\EvepraisalPriceProvider\PriceProvider;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use JsonException;
use RecursiveTree\Seat\EvepraisalPriceProvider\EvepraisalPriceProviderServiceProvider;
use RecursiveTree\Seat\PricesCore\Contracts\IPriceProviderBackend;
use RecursiveTree\Seat\PricesCore\Exceptions\PriceProviderException;
use Seat\Services\Contracts\IPriceable;
use Seat\Services\Helpers\UserAgentBuilder;

class EvePraisalPriceProvider implements IPriceProviderBackend
{

    /**
     * Fetches the prices for the items in $items
     * Implementations should store the computed price directly on the Priceable object using the setPrice method.
     * In case an error occurs, a PriceProviderException should be thrown, so that an error message can be shown to the user.
     *
     * @param Collection<IPriceable> $items The items to appraise
     * @param array $configuration The configuration of this price provider backend.
     * @throws PriceProviderException
     */
    public function getPrices(Collection $items, array $configuration): void
    {
        // evepraisal doesn't like empty requests
        if($items->isEmpty()) return;

        // step 1: Collect TypeIDs we are interested in
        $typeIDs = [];
        foreach ($items as $item){
            $typeIDs[$item->getTypeID()] = null;
        }

        // step 2: Request prices
        $user_agent = (new UserAgentBuilder())
            ->seatPlugin(EvepraisalPriceProviderServiceProvider::class)
            ->defaultComments()
            ->build();

        $client = new \GuzzleHttp\Client([
            'base_uri' => $configuration['evepraisal_instance'],
            'timeout' => $configuration['timeout'],
            'headers' => [
                'User-Agent' => $user_agent,
            ]
        ]);

        //map typeid hash map to evepraisal request format
        $evepraisal_items = array_map(fn ($type_id): array => ['type_id'=>$type_id], array_keys($typeIDs));

        try {
            $response = $client->post('/appraisal/structured.json', [
                'query'=>[
                    'persist'=>'no'
                ],
                'json' => [
                    'market_name' => $configuration['market'] ?? 'jita',
                    'items' =>$evepraisal_items,
                ],
            ]);
            //dd(str($response->getBody()));
            $response = json_decode($response->getBody(), false, 64, JSON_THROW_ON_ERROR);
        } catch (GuzzleException | JsonException $e) {
            throw new PriceProviderException('Failed to load data from evepraisal: '.$e->getMessage(),0,$e);
        }

        foreach ($response->appraisal->items as $item){
            if($configuration['is_buy']) {
                $price_bucket = $item->prices->buy;
            } else {
                $price_bucket = $item->prices->sell;
            }

            $variant = $configuration['variant'];
            if($variant == 'min'){
                $price = $price_bucket->min;
            } elseif ($variant == 'max') {
                $price = $price_bucket->max;
            } elseif ($variant == 'avg') {
                $price = $price_bucket->avg;
            } elseif ($variant == 'median') {
                $price = $price_bucket->median;
            } else {
                $price = $price_bucket->percentile;
            }

            $typeIDs[$item->typeID] = $price;
        }

        // step 3: Feed prices back to system
        foreach ($items as $item){
            $price = $typeIDs[$item->getTypeID()] ?? null;
            if($price === null) {
                throw new PriceProviderException('EvePraisal didn\'t respond with the requested prices.');
            }
            if(!(is_int($price) || is_float($price))){
                throw new PriceProviderException('EvePraisal responded with a non-numerical price.');
            }

            $item->setPrice($price * $item->getAmount());
        }
    }
}