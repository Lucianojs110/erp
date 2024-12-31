<div class="modal fade" id="update_price_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4>Actualizacion de Productos</h4>
            </div>
            <div class="modal-body">
                @can('product.update')
                {!! Form::open(['url' => action('ProductController@selectionUpdate'), 'method' => 'POST', 'id' => 'selection-update-form',
                'class' => 'product_form', 'files' => true ]) !!}
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="typeOfUpdate"> Tipo de actualizacion: </label>
                            <select class="custom-select" id="typeOfUpdate" name="typeOfUpdate" >
                                <option value="percentage">
                                    Porcentaje
                                </option>
                                <option value="fixed" selected>
                                        Valor
                                </option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row" id="percentageValue">
                        <div class="col-lg-12">
                        <div class="form-group">
                            {!! Form::label('increment_percent', 'Porcentaje(%) de incremento de precios a la venta:*') !!} @show_tooltip('Los precios de venta se incrementarán según el porcentaje que ingrese')
                            {!! Form::number('increment_percent', 0, ['class' => 'form-control', 'required', 'id' => 'increment-percent', 'name' => 'increment-percent',
                            'placeholder' => '0' , 'min' => '0']); !!}
                        </div>
                        </div>
                    </div>
                    <div class="row" id="fixedValue">
                        <div class="col-lg-12">
                        <div class="form-group">
                        {!! Form::label('fixed_value', 'Valor a agregar al precio de venta:*') !!} @show_tooltip('A los precios de venta se le sumaran el valor que ingrese')
                        {!! Form::number('fixed_value', 0, ['class' => 'form-control', 'required', 'id' => 'fixed-value', 'name' => 'fixed-value',
                        'placeholder' => '0' , 'min' => '0']); !!}
                        </div>
                        </div>
                    </div>
                    <div class="row">
                            <div class="col-sm-4">
                                <label for="example-initial-value">Ejemplo - Valor inicial:</label>
                                <input id="example-initial-value" name="example-initial-value" type="number" disabled value="20"/>
                            </div>
                            <div class="col-sm-4">
                                <label for="example-end-value">Ejemplo - Valor final:</label>
                                <input id="example-end-value" name="example-end-value" type="number" disabled value="20"/>
                            </div>
                    </div>
                        <input type="hidden" name="products-ids" id="products-ids" value=""/>
                    {!! Form::close() !!}
                @endcan
              </div>
              <div class="modal-footer">
                <input type="button" name="btn" value="Actualizar precios" id="submitBtn" data-toggle="modal" data-target="#confirm-submit" class="btn btn-primary" />
                  <button type="button" class="btn btn-default no-print" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
        </div>
    </div>