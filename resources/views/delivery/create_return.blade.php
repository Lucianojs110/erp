<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Devolución de mercaderia</h4>
        </div>

        <div class="modal-body">
            
            <form action="{{ route('temporalstock.return.store') }}" method="POST" id="stock_return_form">
                @csrf

                {{-- Make a table where each row is an agent temporal stock, where each column is: product name, quantity, occupied_quantity and an erase row button --}}
                @if (count($temporal_stocks) <= 0)
                    <p>Usted no tiene productos para devolver.</p>
                @else
                    <input type="hidden" name="agent_id" value="{{ $sales_commission_agent }}">
                    <p>La siguiente es una lista de los productos restantes que no se entregaron. <br>
                    Seleccione los productos que desea devolver.</p>
                    <table class="table" id="temporal_stock_table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Sucursal</th>
                                <th>Cantidad disponible</th>
                                <th>Cantidad ocupada</th>
                                <th>Remover</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($temporal_stocks as $temporalAgentStock)
                            <tr>
                                <input type="hidden" name="product_id[]" value="{{ $temporalAgentStock->product_id }}">
                                <input type="hidden" name="location_id[]" value="{{ $temporalAgentStock->location_id }}">
                                <input type="hidden" name="variation_id[]" value="{{ $temporalAgentStock->variation_id }}">
                                <input type="hidden" name="temporal_stock_id[]" value="{{ $temporalAgentStock->id }}">
                                <td>{{ $temporalAgentStock->product_name }}</td>
                                <td>{{ $temporalAgentStock->location_name }}</td>
                                <td>
                                    <input type="number" name="quantity[]" id="temporal_stock_quantity" value="{{ $temporalAgentStock->quantity - $temporalAgentStock->occupied_quantity }}" min="0" max="{{ $temporalAgentStock->quantity - $temporalAgentStock->occupied_quantity }}">
                                </td>
                                <td>
                                    <input type="number" name="occupied_quantity[]" id="temporal_stock_occupied_quantity" value="{{ $temporalAgentStock->occupied_quantity }}" disabled>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger remove_stock_row">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                <button type="submit" class="btn btn-primary" @if(count($temporal_stocks) <= 0) disabled @endif>Generar devolución</button>
            </form>
        </div>
    </div>
</div>