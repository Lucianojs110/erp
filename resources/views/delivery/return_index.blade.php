@extends('layouts.app')
@section('title', 'Devoluciones')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Devoluciones de mercaderia</h1>
</section>

<!-- Main content -->
<section class="content">

    @component('components.widget', ['class' => 'box-primary', 'title' => 'Stock temporal'])
    @slot('tool')

    <div class="box-tools">
        <button data-href="{{action('AgentTemporalStockController@createReturn')}}" data-container=".temporal_stock_modal" class="btn btn-primary temporal_stock_modal_button">
            <i class="fa fa-plus
            "></i> Crear devolución de stock
        </button>
    </div>
    @endslot
    @can('return.view')
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="agent_temporal_stock_table">
            <thead>
                <tr>
                    <th>@lang( 'product.product_name' )</th>
                    <th>@lang( 'lang_v1.quantity' )</th>
                    <th>@lang( 'purchase.business_location' )</th>
                </tr>
            </thead>
        </table>
    </div>
    @endcan
    @endcomponent

    <div class="modal fade temporal_stock_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

</section>

@endsection