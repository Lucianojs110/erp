@extends('layouts.app')
@section('title', __('lang_v1.add_opening_stock'))

@section('content')

    <section class="content-header">
        <h1>@lang('lang_v1.add_opening_stock')</h1>
    </section>

    <section class="content">
        {!! Form::open([
            'url' => $form_action ?? action('OpeningStockController@save'),
            'method' => 'post',
            'id' => 'add_opening_stock_form',
        ]) !!}

        {!! Form::hidden('product_id', $product->id) !!}

        @include('opening_stock.form-part')

        <div class="row">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary pull-right">
                    @lang('messages.save')
                </button>
            </div>
        </div>

        {!! Form::close() !!}
    </section>
@stop

@section('javascript')
    <script src="{{ asset('js/opening_stock.js?v=' . $asset_v) }}"></script>
@endsection
