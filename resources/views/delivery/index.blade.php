@extends('layouts.app')
@section('title', __( 'lang_v1.deliveries' ))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'lang_v1.deliveries' )</h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'lang_v1.deliveries' )])
        @can('delivery.create')
            @slot('tool')
                <div class="box-tools">
                    <button type="button" class="btn btn-block btn-primary btn-modal" 
                        data-href="{{action('DeliveryController@create')}}" 
                        data-container=".delivery_modal">
                        <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
                </div>
            @endslot
        @endcan
        @can('delivery.view')
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="deliveries_table">
                    <thead>
                        <tr>
                            <th>@lang( 'lang_v1.delivery_date' )</th>
                            <th>@lang( 'business.business_location' )</th>
                            <th>@lang( 'lang_v1.sales_commission_agent' )</th>
                            <th>@lang( 'contact.customer' )</th>
                            <th>@lang( 'messages.action' )</th>
                        </tr>
                    </thead>
                </table>
                </div>
        @endcan
    @endcomponent

    <div class="modal fade delivery_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

    <div class="modal fade payment_modal" tabindex="-1" role="dialog" 
        aria-labelledby="gridSystemModalLabel">
    </div>

</section>

<!-- /.content -->


@endsection
