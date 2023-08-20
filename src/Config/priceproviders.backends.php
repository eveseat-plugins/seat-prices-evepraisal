<?php

use RecursiveTree\Seat\EvepraisalPriceProvider\PriceProvider\EvePraisalPriceProvider;

return [
    'recursivetree/seat-prices-evepraisal' => [
        'backend'=> EvePraisalPriceProvider::class,
        'label'=>'evepraisalpriceprovider::evepraisal.evepraisal_price_provider',
        'plugin'=>'recursivetree/seat-prices-evepraisal',
        'settings_route' => 'evepraisalpriceprovider::configuration',
    ]
];