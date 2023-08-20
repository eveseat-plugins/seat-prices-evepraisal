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
                    <label for="timeout">{{ trans('evepraisalpriceprovider::evepraisal.timeout') }}</label>
                    <input required type="number" name="timeout" id="timeout" class="form-control" placeholder="{{ trans('pricescore::settings.timeout_placeholder') }}" value="{{ $timeout ?? 5 }}" min="0" step="1">
                    <small class="text-muted">{{ trans('evepraisalpriceprovider::evepraisal.timeout_description') }}</small>
                </div>

                <button type="submit" class="btn btn-primary">{{ trans('pricescore::priceprovider.save')  }}</button>
            </form>
        </div>
    </div>

@endsection
