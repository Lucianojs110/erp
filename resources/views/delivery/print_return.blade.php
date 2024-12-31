@extends('layouts.app')
@section('title', __( 'lang_v1.deliveries' ))

@section('content')
<div class="container" id="delivery_receipt_content">
    <div class="modal-content">
        <div class="modal-header">
            <h1>Comprobante de devolucion</h1>
            <div class="customer-details">
            <h2>Devolucion No. {{ $return_number }}</h2>
        </div>
        </div>
        
        <div class="modal-body">
            <h2>Detalle de productos</h2>
            <!-- Add your delivery products here -->
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Ubicacion comercial</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($return as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->location_name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <p>Generado en: {{ date('Y-m-d H:i:s') }}</p>
            <div class="text-center">
                <div class="row">
                    <div class="col-md-6">
                    <a href="javascript:history.back()" class="btn btn-secondary"><i class="fa fa-arrow-left" aria-hidden="true"></i>Volver</a>
                    </div>
                    <div class="col-md-6">
                    <a href="#" class="print-delivery-receipt btn btn-success" data-href="{{route('temporalstock.print_return', $return_number)}}"><i class="fa fa-print" aria-hidden="true"></i> @lang("messages.print")</a>
                    </div>
                </div>
                
                
            </div>
            <div class="modal-footer">
                
            </div>
        </div>
    </div>
</div>
@endsection