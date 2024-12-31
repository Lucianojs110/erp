<div class="modal-dialog" role="document">
  <div class="modal-content">

    {!! Form::open(['url' => action('SalesCommissionAgentController@store'), 'method' => 'post', 'id' => 'sale_commission_agent_form' ]) !!}

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'lang_v1.add_sales_commission_agent' )</h4>
      <p class="help-block"> Aquí se agregará un usuario apto para realizar ventas de repartos y manejar su stock temporal y devoluciones. La contraseña por defecto es <strong>repartidor</strong>. </p>
    </div>

    <div class="modal-body">
      <div class="row">
        <div class="col-md-2">
          <div class="form-group">
            {!! Form::label('surname', __( 'business.prefix' ) . ':') !!}
            {!! Form::text('surname', null, ['class' => 'form-control', 'placeholder' => __( 'business.prefix_placeholder' ) ]); !!}
          </div>
        </div>
        <div class="col-md-5">
          <div class="form-group">
            {!! Form::label('first_name', __( 'business.first_name' ) . ':*') !!}
            {!! Form::text('first_name', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'business.first_name' ) ]); !!}
          </div>
        </div>
        <div class="col-md-5">
          <div class="form-group">
            {!! Form::label('last_name', __( 'business.last_name' ) . ':') !!}
            {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => __( 'business.last_name' ) ]); !!}
          </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
              {!! Form::label('username', __( 'business.username' ) . ':') !!}
              @if(!empty($username_ext))
              <div class="input-group">
                {!! Form::text('username', null, ['class' => 'form-control', 'placeholder' => __( 'business.username' ) ]); !!}
                <span class="input-group-addon">{{$username_ext}}</span>
              </div>
              <p class="help-block" id="show_username"></p>
              @else
              {!! Form::text('username', null, ['class' => 'form-control', 'placeholder' => __( 'business.username' ) ]); !!}
              @endif
              <p class="help-block">@lang('lang_v1.username_help')</p>
            </div>
          </div>
        <div class="clearfix"></div>
        <div class="col-md-6">
          <div class="form-group">
            {!! Form::label('email', __( 'business.email' ) . ':') !!}
            {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => __( 'business.email' ) ]); !!}
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            {!! Form::label('contact_no', __( 'lang_v1.contact_no' ) . ':') !!}
            {!! Form::text('contact_no', null, ['class' => 'form-control', 'placeholder' => __( 'lang_v1.contact_no' ) ]); !!}
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            {!! Form::label('address', __( 'business.address' ) . ':') !!}
            {!! Form::textarea('address', null, ['class' => 'form-control', 'placeholder' => __( 'business.address'), 'rows' => 3 ]); !!}
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            {!! Form::label('cmmsn_percent', __( 'lang_v1.cmmsn_percent' ) . ':') !!}
            {!! Form::number('cmmsn_percent', null, ['class' => 'form-control', 'placeholder' => __( 'lang_v1.cmmsn_percent' ), 'step' => 0.01, 'min' => 0, 'max' => 100,'required' ]); !!}
          </div>
        </div>

      </div>
    </div>

    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>

    {!! Form::close() !!}

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->