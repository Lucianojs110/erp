<div class="modal-dialog" role="document">
  <div class="modal-content">

    {!! Form::model($withholdings, ['url' => action('WithholdingController@update', [$withholdings->id]), 'method' => 'PUT', 'id' => 'withholdings_edit_form' ]) !!}

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Editar la retención o percepción</h4>
    </div>

    <div class="modal-body">
    <div class="form-group">
        {!! Form::label('name', 'Nombre' . ':*') !!}
          {!! Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'tax_rate.name' )]); !!}
      </div>

      <div class="form-group">
          {!! Form::label('percentage', 'Porcentaje' . ':*') !!}
          {!! Form::text('percentage', null, ['class' => 'form-control', 'required', 'placeholder' => 'Porcentaje', 'oninput' => 'this.value = this.value.replace(/[^0-9.]/g, "")']) !!}
      </div>

      <div class="form-group">
          {!! Form::label('type', 'Categoría' . ':*') !!}
          {!! Form::select('type', [
              1 => 'Percepciones',
              2 => 'Retenciones',
          ], null, ['class' => 'form-control select2', 'required']); !!}
      </div>

    </div>

    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">@lang( 'messages.update' )</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>

    {!! Form::close() !!}

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->