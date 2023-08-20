<?php

namespace RecursiveTree\Seat\EvepraisalPriceProvider\Http\Controllers;

use Illuminate\Http\Request;
use RecursiveTree\Seat\PricesCore\Models\PriceProviderInstance;
use Seat\Web\Http\Controllers\Controller;

class EvepraisalPriceProviderController extends Controller
{
    public function configuration(Request $request){
        $existing = PriceProviderInstance::find($request->id);

        $name = $request->name ?? $existing->name;
        $instance = $existing->configuration['evepraisal_instance'] ?? '';
        $id = $request->id;
        $timeout = $existing->configuration['timeout'] ?? 5;

        return view('evepraisalpriceprovider::configuration', compact('name', 'instance', 'id', 'timeout'));
    }

    public function configurationPost(Request $request) {
        $request->validate([
           'id'=>'nullable|integer',
           'name'=>'required|string',
           'instance'=>'required|string',
            'timeout'=>'required|integer'
        ]);

        $model = PriceProviderInstance::findOrNew($request->id);
        $model->name = $request->name;
        $model->backend = 'recursivetree/seat-prices-evepraisal';
        $model->configuration = [
            'evepraisal_instance' => $request->instance,
            'timeout' => $request->timeout
        ];
        $model->save();

        return redirect()->route('pricescore::settings')->with('success',trans('evepraisalpriceprovider::evepraisal.edit_price_provider_success'));
    }
}