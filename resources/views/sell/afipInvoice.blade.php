@extends('layouts.app')
@section('title', __( 'lang_v1.all_sales'))

@section('content')

<style>

@media print{
  #oculto-impresion{
    display: none !important;
  }
}
    .loader {
      
        box-sizing: border-box;
        color: black;
        text-align: center;
        background-color: white;
        box-sizing: border-box;
        transition: height .2s;
        z-index: 999;

    }
</style>

<div class="loader" id="loader" role="alert">
    <h1> Se esta registrando su factura... </h1>
</div>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 id="numfac">Factura  {{$sell->type_invoice}} N°{{$sell->num_invoice_afip}}<small>(venta n°: <span class="text-success">#{{$sell->invoice_no}})</span></small></h1>
</section>
<!-- Main content -->
<section class="content" id="oculto-impresion"> 



    <div class="row">
        <div class="col-xs-12">
        @if($sell->afip_invoice_date)
            <p class="pull-right" id="fechafac"><b>@lang('messages.date'):</b> {{ @format_date($sell->afip_invoice_date) }}</p>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <b>{{ __('sale.invoice_no') }}:</b> #{{ $sell->invoice_no }}<br>
            <b>{{ __('sale.status') }}:</b>
            @if($sell->status == 'draft' && $sell->is_quotation == 1)
            {{ __('lang_v1.quotation') }}
            @else
            {{ ucfirst( $sell->status ) }}
            @endif
            <br>
            <b>{{ __('sale.payment_status') }}:</b> {{ ucfirst( $sell->payment_status ) }}<br>
        </div>
        <div class="col-sm-4">
            <b>{{ __('sale.customer_name') }}:</b> {{ $sell->contact->name }}<br>
            <b>CUIT:</b> {{ $sell->contact->tax_number }}<br>
            <b>{{ __('business.address') }}:</b><br>
            @if(!empty($sell->billing_address()))
            {{$sell->billing_address()}}
            @else
            @if($sell->contact->landmark)
            {{ $sell->contact->landmark }},
            @endif

            {{ $sell->contact->city }}

            @if($sell->contact->state)
            {{ ', ' . $sell->contact->state }}
            @endif
            <br>
            @if($sell->contact->country)
            {{ $sell->contact->country }}
            @endif
            @endif

        </div>
        <div class="col-sm-4">
            @if(in_array('tables' ,$enabled_modules))
            <strong>@lang('restaurant.table'):</strong>
            {{$sell->table->name ?? ''}}<br>
            @endif
            @if(in_array('service_staff' ,$enabled_modules))
            <strong>@lang('restaurant.service_staff'):</strong>
            {{$sell->service_staff->user_full_name ?? ''}}<br>
            @endif

            @if(!empty($sell->shipping_address()))
            <strong>@lang('sale.shipping'):</strong><br>
            {{$sell->shipping_address()}}
            @endif
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <h4>{{ __('sale.products') }}:</h4>
        </div>

        <div class="col-sm-12 col-xs-12">
            <div class="table-responsive">
                @include('sale_pos.partials.sale_line_details')
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <h4>{{ __('sale.payment_info') }}:</h4>
        </div>
        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table bg-gray">
                    <tr class="bg-green">
                        <th>#</th>
                        <th>{{ __('messages.date') }}</th>
                        <th>{{ __('purchase.ref_no') }}</th>
                        <th>{{ __('sale.amount') }}</th>
                        <th>{{ __('sale.payment_mode') }}</th>
                        <th>{{ __('sale.payment_note') }}</th>
                    </tr>
                    @php
                    $total_paid = 0;
                    @endphp
                    @foreach($sell->payment_lines as $payment_line)
                    @php
                    if($payment_line->is_return == 1){
                    $total_paid -= $payment_line->amount;
                    } else {
                    $total_paid += $payment_line->amount;
                    }
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ @format_date($payment_line->paid_on) }}</td>
                        <td>{{ $payment_line->payment_ref_no }}</td>
                        <td><span class="display_currency" data-currency_symbol="true">{{ $payment_line->amount }}</span></td>
                        <td>
                            {{ $payment_types[$payment_line->method] or $payment_line->method }}
                            @if($payment_line->is_return == 1)
                            <br />
                            ( {{ __('lang_v1.change_return') }} )
                            @endif
                        </td>
                        <td>@if($payment_line->note)
                            {{ ucfirst($payment_line->note) }}
                            @else
                            --
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table bg-gray">
                    <tr>
                        <th>{{ __('sale.total') }}: </th>
                        <td></td>
                        <td><span class="display_currency pull-right" data-currency_symbol="true">{{ $sell->total_before_tax }}</span></td>
                    </tr>
                    <tr>
                        <th>{{ __('sale.discount') }}:</th>
                        <td><b>(-)</b></td>
                        <td><span class="pull-right">{{ $sell->discount_amount }} @if( $sell->discount_type == 'percentage') {{ '%'}} @endif</span></td>
                    </tr>
                    <tr>
                        <th>{{ __('sale.order_tax') }}:</th>
                        <td><b>(+)</b></td>
                        <td class="text-right">
                            @if(!empty($order_taxes))
                            @foreach($order_taxes as $k => $v)
                            <strong><small>{{$k}}</small></strong> - <span class="display_currency pull-right" data-currency_symbol="true">{{ $v }}</span><br>
                            @endforeach
                            @else
                            0.00
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('sale.shipping') }}: @if($sell->shipping_details)({{$sell->shipping_details}}) @endif</th>
                        <td><b>(+)</b></td>
                        <td><span class="display_currency pull-right" data-currency_symbol="true">{{ $sell->shipping_charges }}</span></td>
                    </tr>
                    <tr>
                        <th>{{ __('sale.total_payable') }}: </th>
                        <td></td>
                        <td><span class="display_currency pull-right">{{ $sell->final_total }}</span></td>
                    </tr>
                    <tr>
                        <th>{{ __('sale.total_paid') }}:</th>
                        <td></td>
                        <td><span class="display_currency pull-right" data-currency_symbol="true">{{ $total_paid }}</span></td>
                    </tr>
                    <tr>
                        <th>{{ __('sale.total_remaining') }}:</th>
                        <td></td>
                        <td><span class="display_currency pull-right" data-currency_symbol="true">{{ $sell->final_total - $total_paid }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6" id="cae">
            <strong>CAE:</strong><br>
            <p class="well well-sm no-shadow bg-gray">
                @if($sell->cae)
                {{ $sell->cae }}
                @else
                --
                @endif
            </p>
        </div>
        <div class="col-sm-6" id="expcae">
            <strong>Vencimiento Cae:</strong><br>
            <p class="well well-sm no-shadow bg-gray">
                @if($sell->exp_cae)
                {{ $sell->exp_cae}}
                @else
                --
                @endif
            </p>
        </div>

   
        <div class="col-sm-6">

            <button id="btn-sol-cae" class="btn btn-primary">Registrar Factura</button>
            <br>
            <button class="btn btn-primary">
            <a href="#" style="color:white" class="print-invoice" data-href="{{route('sell.printInvoice', $sell->id)}}"><i class="fa fa-file-text-o" aria-hidden="true"></i> imprimir</a>
            </button>        
        </div>
    </div>
    </div>

    <input type="hidden" class="form-control" id="sell_id" value="{{ $sell->id }}">
    <input type="hidden" class="form-control" id="sell_cae" value="{{ $sell->cae }}">
    </div>
</section>

@stop



@section('javascript')

<script type="text/javascript">
    $(document).ready(function() {

        if($("#sell_cae").val() == '' ){
            document.getElementById("btn-sol-cae").style.display = "block";
            setTimeout(function (){ solicitar_cae()  }, 5000);
        }else{
            document.getElementById("loader").style.display = "none";
            document.getElementById("btn-sol-cae").style.display = "none";
        }

        $("#btn-sol-cae").click(function() {
            solicitar_cae()
        });

    });

    function solicitar_cae() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "{{ route('requestCae') }}",
            dataType: "json",
            beforeSend: function() {
                document.getElementById("loader").style.display = "block";
            },
            data: {
                sell_id: $("#sell_id").val(),
            },
            success: function(data) {
                document.getElementById("loader").style.display = "none";
                $("#cae").load(" #cae");
                $("#numfac").load(" #numfac");
                $("#expcae").load(" #expcae");
                $("#fechafac").load(" #fechafac");
                document.getElementById("btn-sol-cae").style.display = "none";
                toastr.success("Comprobante registrado")
                console.log(data)
            },
            error: function(data) {
                toastr.warning("Algo ha salido mal al conectarse al webserver afip")
                alert('No se realizo la operacion. Respuesta del servidor: ' + data.responseJSON.message)
                document.getElementById("btn-sol-cae").style.display = "block";
                document.getElementById("loader").style.display = "none";
            },

        });
        return false;
    }
</script>

@endsection