@extends('layouts.app')
@section('title', 'Productos')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Actualizacion de precios masiva
        <small>Modifique con precaucion</small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="box">
        <div class="box-header">
        	<h3 class="box-title">Opciones</h3>
        </div>
        <div class="box-body">
            @can('product.update')
            {!! Form::open(['url' => action('ProductController@massiveUpdatePercent'), 'method' => 'POST', 'id' => 'massive-update-form',
            'class' => 'product_form', 'files' => true ]) !!}
                <div class="row">
                    <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('increment_percent', 'Porcentaje(%) de incremento de precios a la venta:*') !!} @show_tooltip('Los precios de venta se incrementarán según el porcentaje que ingrese')
                        {!! Form::number('increment_percent', 0, ['class' => 'form-control', 'required', 'id' => 'increment-percent', 'name' => 'increment-percent',
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
                
                <div class="row">
                    <div class="col-sm-12"><h4>Filtros</h4></div>
                    <div class="col-sm-4 @if(!session('business.enable_brand')) hide @endif">
                        <div class="form-group">
                          {!! Form::label('brand_id', 'Marca:') !!}
                            {!! Form::select('brand_id', $brands, null, ['placeholder' => 'Por favor seleccione', 'class' => 'form-control select2']); !!}
                        </div>
                    </div>
                    <div class="col-sm-4 @if(!session('business.enable_category')) hide @endif">
                        <div class="form-group">
                          {!! Form::label('category_id', 'Categoría:') !!}
                            {!! Form::select('category_id', $categories, null, ['placeholder' => 'Por favor seleccione', 'class' => 'form-control select2']); !!}
                        </div>
                      </div>
              
                      <div class="col-sm-4 @if(!(session('business.enable_category') && session('business.enable_sub_category'))) hide @endif">
                        <div class="form-group">
                          {!! Form::label('sub_category_id', 'Subcategoría:') !!}
                            {!! Form::select('sub_category_id', array(), null, ['placeholder' => 'Por favor seleccione', 'class' => 'form-control select2']); !!}
                        </div>
                      </div>
                </div>
                
                <div class="row">
                <div class="col-sm-12">
                    <div class="pull-right">
                        <input type="button" name="btn" value="Actualizar precios" id="submitBtn" data-toggle="modal" data-target="#confirm-submit" class="btn btn-primary" />
                    </div>
                </div>
                </div>
            @endcan
        </div>
    </div>
    <div class="modal fade" id="confirm-submit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Confirmar actualizacion</h3>
                </div>
                <div class="modal-body">
                    ¿Desea realmente actualizar los precios con el porcentaje ingresado? - El procesamiento podría demorar unos minutos
                    <input type="text" style="text-align: right" id="increment" disabled/>%
                </div>
    
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <a href="#" id="submit" class="btn btn-success success">Actualizar</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

@endsection

@section('javascript')

    <script>
        $(document).ready(function(){
            var initialValue = $('#example-initial-value').val();
            $('#increment-percent').on("input" ,function(){
                var endvalue = ( ($(this).val()/100) + 1) * initialValue;
                $('#example-end-value').val(endvalue);
            });
        });

        $('#submitBtn').click(function() {
            /* when the button in the form, display the entered values in the modal */
            $('#increment').val($('#increment-percent').val());
        });

        $('#submit').click(function(){
            if($('#increment-percent').val() == 0){
                alert("Ingrese un % válido distinto de 0");
            }else{
                /* when the submit button in the modal is clicked, submit the form */
                $('#massive-update-form').submit();
            }
        });
    </script>
@endsection