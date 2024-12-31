@extends('layouts.app')
@section('title', __( 'lang_v1.deliveries' ))

@section('content')
<div class="modal-content" id="delivery_receipt_content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Detalles del reparto</h4>
    </div>
    <div class="modal-body">
        <p><strong>@lang('contact.customer'):</strong> {{ $customer->name }}</p>
        <p><strong>@lang('business.business_location'):</strong> {{ $delivery->businessLocation->name }}</p>
        <p><strong>@lang('messages.date'):</strong> {{ \Carbon\Carbon::parse($delivery->delivered_at)->format('d/m/Y') }}</p>
        {{-- Delivery details table --}}
        <table class="table table-bordered table-striped" id="delivery_details_table">
            <thead>
                <tr>
                    <th>@lang('product.product_name')</th>
                    <th>@lang('sale.qty')</th>
                    <th>@lang('sale.unit_price')</th>
                    <th>@lang('sale.subtotal')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($delivery->deliveryDetails as $delivery_detail)
                <tr>
                    <td>{{ $delivery_detail->product->name }}</td>
                    <td>{{ $delivery_detail->quantity }}</td>
                    <td>{{ number_format($delivery_detail->variation->sell_price_inc_tax, 2) }}</td>
                    <td>{{ number_format($delivery_detail->quantity * $delivery_detail->variation->sell_price_inc_tax, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Total --}}
        <div class="row">
            <div class="col-md-6 col-md-offset-6">
                <table class="table table-bordered">
                    <tr>
                        <th>@lang('lang_v1.shipping_charges'):</th>
                        <td>{{ number_format($agent->commission_percentage * $delivery->transaction->final_total, 2) }}</td>
                    </tr>
                    <tr>
                        <th>@lang('sale.total'):</th>
                        <td>{{ number_format($delivery->transaction->final_total, 2) }}</td>
                    </tr>
                </table>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                {{-- go to POS edit button --}}
                <div class="text-center">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="javascript:history.back()" class="btn btn-secondary"><i class="fa fa-arrow-left" aria-hidden="true"></i> Volver</a>
                        </div>
                        <div class="col-md-6">

                            @if($delivery->transaction_id != null && $delivery->transaction->status != 'final')

                            @can('sell.update')
                            {{-- center button class div --}}

                            <a href="{{action('SellPosController@edit', [$delivery->transaction->id])}}" class="btn btn-primary"><i class="fa fa-money" aria-hidden="true"></i> POS</a>

                            @endcan
                            @elseif($delivery->transaction_id !=null && $delivery->transaction->status == 'final')

                            <a href="#" class="btn print-delivery-receipt btn-success" data-href="{{route('delivery.printDeliveryReceipt', [$delivery->id])}}"><i class="fa fa-print" aria-hidden="true"></i> @lang("messages.print")</a>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection