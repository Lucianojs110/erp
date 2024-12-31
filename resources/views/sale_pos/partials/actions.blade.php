<div class="btn-group">
    <button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">
        Acciones
        <span class="caret"></span><span class="sr-only">
            Toggle Dropdown
        </span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right" role="menu">
        @if(auth()->user()->can("sell.view") || auth()->user()->can("direct_sell.access") )
            <li>
                <a href="#" data-href="{{action('SellController@show', $sell->id)}}" class="btn-modal" data-container=".view_modal">
                    <i class="fa fa-external-link" aria-hidden="true"></i>
                    Ver
                </a>
            </li>
        @endif
        @if($sell->is_direct_sale && auth()->user()->can("sell.update"))
            <li>
                <a target="_blank" href="{{action('SellPosController@edit', $sell->id)}}">
                    <i class="glyphicon glyphicon-edit"></i>
                    Editar
                </a>
            </li>
        @elseif(auth()->user()->can("direct_sell.access"))
            <li>
                <a target="_blank" href="{{action('SellController@edit', $sell->id)}}">
                    <i class="glyphicon glyphicon-edit"></i> 
                    Editar
                </a>
            </li>
        @endif
        @can("sell.delete")
            <li>
                <a href="{{action('SellPosController@destroy', $sell->id)}}" class="delete-sale">
                    <i class="fa fa-trash"></i>
                    Eliminar
                </a>
            </li>
        @endcan
        @if(auth()->user()->can("sell.view") || auth()->user()->can("direct_sell.access") )
            <li>
                <a href="#" class="print-invoice" data-href="{{route('sell.printInvoice', $sell->id)}}">
                    <i class="fa fa-print" aria-hidden="true"></i>
                    Imprimir
                </a>
            </li>
        @endif
        <li class="divider"></li>
        @if($sell->is_quotation == 0 || $sell->quotation_status == App\Extras\QuotationStatus::APROBADO)
            @if($sell->payment_status != 'pagado')
                @if(auth()->user()->can("sell.create") || auth()->user()->can("direct_sell.access") )
                    <li>
                        <a href="{{action('TransactionPaymentController@addPayment', $sell->id)}}" class="add_payment_modal">
                            <i class="fa fa-money"></i>
                            Agregar pago
                        </a>
                    </li>
                @endif
            @endif
            <li>
                <a href="{{ action("TransactionPaymentController@show", $sell->id)}}" class="view_payment_modal">
                    <i class="fa fa-money"></i> 
                    Ver pagos
                </a>
            </li>
        @elseif($sell->quotation_status == App\Extras\QuotationStatus::PENDIENTE)
            <li>
                <a href="{{ route('quotation.status', ['id'=>$sell->id, 'status'=>App\Extras\QuotationStatus::APROBADO]) }}">
                    <i class="fa fa-pencil-square" aria-hidden="true"></i>
                    Aprobar
                </a>
            </li>
            <li>
                <a href="{{ route('quotation.status', ['id'=>$sell->id, 'status'=>App\Extras\QuotationStatus::RECHAZADO]) }}">
                    <i class="fa fa-pencil-square" aria-hidden="true"></i>
                    Rechazar
                </a>
            </li>
        @endif
    </ul>
</div>