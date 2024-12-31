@extends('layouts.app')
@section('title', 'Stock temporal')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Stock temporal</h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
    {{-- Add stock return button --}}

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
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="agent_temporal_stock_table">
                    <thead>
                        <tr>
                            <th>@lang( 'product.product_name' )</th>
                            <th>@lang( 'lang_v1.quantity' )</th>
                            <th>@lang( 'lang_v1.sales_commission_agent' )</th>
                            <th>@lang( 'purchase.business_location' )</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>

    @endcan
    @endcomponent

    @component('components.widget', ['class' => 'box-primary', 'title' => 'Devoluciones de stock'])
    @can('return.view')
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="agent_stock_returns_table">
                    <thead>
                        <tr>
                            <th>Numero de devolucion</th>
                            <th>Cantidad de productos devueltos (unidades)</th>
                            <th>@lang( 'business.business_location' )</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
    @endcan

    @endcomponent

    <div class="modal fade temporal_stock_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

</section>



@endsection