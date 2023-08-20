<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['web', 'auth', 'locale'],
    'prefix' => '/prices-evepraisal',
    'namespace'=>'RecursiveTree\Seat\EvepraisalPriceProvider\Http\Controllers'
], function () {
    Route::get('/configuration')
        ->name('evepraisalpriceprovider::configuration')
        ->uses('EvepraisalPriceProviderController@configuration')
        ->middleware('can:pricescore.settings');

    Route::post('/configuration')
        ->name('evepraisalpriceprovider::configuration.post')
        ->uses('EvepraisalPriceProviderController@configurationPost')
        ->middleware('can:pricescore.settings');
});