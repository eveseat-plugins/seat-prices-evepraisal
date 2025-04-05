@extends('web::layouts.app')

@section('title', trans('evepraisalpriceprovider::evepraisal.edit_price_provider'))
@section('page_header', trans('evepraisalpriceprovider::evepraisal.edit_price_provider'))
@section('page_description', trans('evepraisalpriceprovider::evepraisal.edit_price_provider'))

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ trans('evepraisalpriceprovider::evepraisal.edit_price_provider') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('evepraisalpriceprovider::configuration.post') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $id ?? "" }}">

                <div class="form-group">
                    <label for="name">{{ trans('pricescore::settings.name') }}</label>
                    <input required type="text" name="name" id="name" class="form-control" placeholder="{{ trans('pricescore::settings.name_placeholder') }}" value="{{ $name ?? '' }}">
                </div>

                <div class="form-group">
                    <label for="instance">{{ trans('evepraisalpriceprovider::evepraisal.evepraisal_instance') }}</label>
                    <input required type="text" name="instance" id="instance" class="form-control" placeholder="{{ trans('evepraisalpriceprovider::evepraisal.evepraisal_instance_placeholder') }}" value="{{ $instance ?? '' }}">
                    <small class="text-muted">{{ trans('evepraisalpriceprovider::evepraisal.evepraisal_instance_description') }}</small>
                </div>

                <div class="form-group">
                    <label for="market">{{ trans('evepraisalpriceprovider::evepraisal.evepraisal_market') }}</label>
                    <input required type="text" name="market" id="market" class="form-control" placeholder="{{ trans('evepraisalpriceprovider::evepraisal.evepraisal_market_placeholder') }}" value="{{ $market ?? 'jita' }}">
                    <small class="text-muted">{{ trans('evepraisalpriceprovider::evepraisal.evepraisal_market_description') }}</small>
                </div>                

                <div class="form-group">
                    <label for="timeout">{{ trans('evepraisalpriceprovider::evepraisal.timeout') }}</label>
                    <input required type="number" name="timeout" id="timeout" class="form-control" placeholder="{{ trans('pricescore::settings.timeout_placeholder') }}" value="{{ $timeout ?? 5 }}" min="0" step="1">
                    <small class="text-muted">{{ trans('evepraisalpriceprovider::evepraisal.timeout_description') }}</small>
                </div>

                <div class="form-group">
                    <label for="price_type">{{ trans('evepraisalpriceprovider::evepraisal.price_type') }}</label>
                    <select name="price_type" id="price_type" class="form-control" required>
                        <option value="sell" @if(!$is_buy) selected @endif>{{ trans('evepraisalpriceprovider::evepraisal.sell') }}</option>
                        <option value="buy" @if($is_buy) selected @endif>{{ trans('evepraisalpriceprovider::evepraisal.buy') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="price_variant">{{ trans('evepraisalpriceprovider::evepraisal.price_variant') }}</label>
                    <select name="price_variant" id="price_variant" class="form-control" required>
                        <option value="max" @if($price_variant==='max') selected @endif>{{ trans('evepraisalpriceprovider::evepraisal.max') }}</option>
                        <option value="min" @if($price_variant==='min') selected @endif>{{ trans('evepraisalpriceprovider::evepraisal.min') }}</option>
                        <option value="avg" @if($price_variant==='avg') selected @endif>{{ trans('evepraisalpriceprovider::evepraisal.avg') }}</option>
                        <option value="median" @if($price_variant==='median') selected @endif>{{ trans('evepraisalpriceprovider::evepraisal.median') }}</option>
                        <option value="percentile" @if($price_variant==='percentile') selected @endif>{{ trans('evepraisalpriceprovider::evepraisal.percentile') }}</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">{{ trans('pricescore::priceprovider.save')  }}</button>
            </form>
        </div>
    </div>

@endsection
