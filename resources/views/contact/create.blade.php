<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
    @php
    $form_id = 'contact_add_form';
    if(isset($quick_add)){
    $form_id = 'quick_add_contact';
    }
    @endphp
    {!! Form::open(['url' => action('ContactController@store'), 'method' => 'post', 'id' => $form_id ]) !!}

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang('contact.add_contact')</h4>
    </div>

    <div class="modal-body">
      <div class="row">

        <div class="col-md-4">
          <div class="form-group">
            {!! Form::label('type', __('contact.contact_type') . ':*' ) !!}
            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-user"></i>
              </span>
              {!! Form::select('type', $types, null , ['class' => 'form-control', 'id' => 'contact_type','placeholder' => __('messages.please_select'), 'required']); !!}
            </div>
          </div>
        </div>


        <div class="col-md-4">
          <div class="form-group">
            DNI o CUIT
            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-info"></i>
              </span>
              {!! Form::select('tipo_doc', [
                'DNI' => 'DNI', 
                'CUIT' => 'CUIT', 
                'CDI' => 'CDI', 
                'LE' => 'LE', 
                'LC' => 'LC', 
                'CI Extranjera' => 'CI Extranjera', 
                'en tramite' => 'en tramite',
                'Acta Nacimiento' => 'Acta Nacimiento',
                'CI Bs As RNP' => 'CI Bs As RNP',
                'Pasaporte' => 'Pasaporte', 
                'CI Policia Federal' => 'CI Policia Federal',
                'CI Buenos Aires' => 'CI Buenos Aires',
                'CI Catamarca' => 'CI Catamarca',
                'CI Cordoba' => 'CI Cordoba',
                'CI Corrientes' => 'CI Corrientes',
                'CI Entre Rios' => 'CI Entre Rios',
                'CI Jujuy' => 'CI Jujuy',
                'CI Mendoza' => 'CI Mendoza',
                'CI La Rioja' => 'CI La Rioja',
                'CI Salta' => 'CI Salta',
                'CI San Juan' => 'CI San Juan',
                'CI San Luis' => 'CI San Luis',
                'CI Santa Fe' => 'CI Santa Fe',
                'CI Santiago del Estero' => 'CI Santiago del Estero',
                'CI Tucuman' => 'CI Tucuman',
                'CI Chaco' => 'CI Chaco',
                'CI Chubut' => 'CI Chubut',
                'CI Formosa' => 'CI Formosa',
                'CI Misiones' => 'CI Misiones',
                'CI Neuquen' => 'CI Neuquen',
                'CI La Pampa' => 'CI La Pampa',
                'CI Rio Negro' => 'CI Rio Negro',
                'CI Santa Cruz' => 'CI Santa Cruz',
                'CI Tierra del Fuego' => 'CI Tierra del Fuego',
                'Doc Otro' => 'Doc Otro'
                ], 
                '', ['class' => 'form-control pull-left','placeholder' => __('messages.please_select')]); !!}
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            Numero de CUIT/DNI
            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-info"></i>
              </span>
              <input type="text"  name="tax_number"  id="tax_number" class="form-control" placeholder="Número de documento...">
            </div>
          </div>
        </div>


      </div>

      <div class="row">


      
      <div class="col-md-4">
          <div class="form-group">
            {!! Form::label('name', __('contact.name') . ':*') !!}
            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-user"></i>
              </span>
              {!! Form::text('name', null, ['class' => 'form-control','placeholder' => __('contact.name'), 'required']); !!}
            </div>
          </div>
        </div>



        <div class="col-md-4">
          <div class="form-group">
            {!! Form::label('supplier_business_name', __('business.business_name') . ':*') !!}
            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-briefcase"></i>
              </span>
              {!! Form::text('supplier_business_name', null, ['class' => 'form-control', 'required', 'placeholder' => __('business.business_name')]); !!}
            </div>
          </div>
        </div>




  

     
        <div class="col-md-4">
          <div class="form-group">
            {!! Form::label('contact_id', __('lang_v1.contact_id') . ':') !!}
            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-id-badge"></i>
              </span>
              {!! Form::text('contact_id', null, ['class' => 'form-control','placeholder' => __('lang_v1.contact_id')]); !!}
            </div>
          </div>
        </div>


      </div>

      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            IVA
            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-info"></i>
              </span>
              {!! Form::select('iva', ['RESPONSABLE INSCRIPTO' => 'RESPONSABLE INSCRIPTO', 'CONSUMIDOR FINAL' => 'CONSUMIDOR FINAL', 'MONOTRIBUTO' => 'MONOTRIBUTO', 'EXENTO' => 'EXENTO'], '', ['class' => 'form-control pull-left','placeholder' => __('messages.please_select')]); !!}
            </div>
          </div>
        </div>


        <div class="col-md-4">
          <div class="form-group">
            {!! Form::label('customer_group_id', __('lang_v1.customer_group') . ':') !!}
            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-users"></i>
              </span>
              {!! Form::select('customer_group_id', $customer_groups, '', ['class' => 'form-control']); !!}
            </div>
          </div>
        </div>


        <div class="col-md-4">
          <div class="form-group">
            {!! Form::label('opening_balance', __('lang_v1.opening_balance') . ':') !!}
            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-money"></i>
              </span>
              {!! Form::text('opening_balance', 0, ['class' => 'form-control input_number']); !!}
            </div>
          </div>
        </div>

      </div>

      <div class="row">

        <div class="col-md-4">
          <div class="form-group">
            <div class="multi-input">
              {!! Form::label('pay_term_number', __('contact.pay_term') . ':') !!} @show_tooltip(__('tooltip.pay_term'))
              <br />
              {!! Form::number('pay_term_number', null, ['class' => 'form-control width-40 pull-left', 'placeholder' => __('contact.pay_term')]); !!}

              {!! Form::select('pay_term_type', ['months' => __('lang_v1.months'), 'days' => __('lang_v1.days')], '', ['class' => 'form-control width-60 pull-left','placeholder' => __('messages.please_select')]); !!}
            </div>
          </div>
        </div>



        <div class="col-md-4 customer_fields">
          <div class="form-group">
            {!! Form::label('credit_limit', __('lang_v1.credit_limit') . ':') !!}
            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-money"></i>
              </span>
              {!! Form::text('credit_limit', null, ['class' => 'form-control input_number']); !!}
            </div>
            <p class="help-block">@lang('lang_v1.credit_limit_help')</p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <hr />
        </div>
        <div class="col-md-3">
          <div class="form-group">
            {!! Form::label('email', __('business.email') . ':') !!}
            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-envelope"></i>
              </span>
              {!! Form::email('email', null, ['class' => 'form-control','placeholder' => __('business.email')]); !!}
            </div>
          </div>
        </div>


        <div class="col-md-3">
          <div class="form-group">
            {!! Form::label('mobile', __('contact.mobile') . ':*') !!}
            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-mobile"></i>
              </span>
              {!! Form::text('mobile', null, ['class' => 'form-control', 'required', 'placeholder' => __('contact.mobile')]); !!}
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            {!! Form::label('alternate_number', __('contact.alternate_contact_number') . ':') !!}
            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-phone"></i>
              </span>
              {!! Form::text('alternate_number', null, ['class' => 'form-control', 'placeholder' => __('contact.alternate_contact_number')]); !!}
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            {!! Form::label('landline', __('contact.landline') . ':') !!}
            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-phone"></i>
              </span>
              {!! Form::text('landline', null, ['class' => 'form-control', 'placeholder' => __('contact.landline')]); !!}
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-3">
          <div class="form-group">
            {!! Form::label('city', __('business.city') . ':') !!}
            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-map-marker"></i>
              </span>
              {!! Form::text('city', null, ['class' => 'form-control', 'placeholder' => __('business.city')]); !!}
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            {!! Form::label('state', __('business.state') . ':') !!}
            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-map-marker"></i>
              </span>
              {!! Form::text('state', null, ['class' => 'form-control', 'placeholder' => __('business.state')]); !!}
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            {!! Form::label('country', __('business.country') . ':') !!}
            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-globe"></i>
              </span>
              {!! Form::text('country', null, ['class' => 'form-control', 'placeholder' => __('business.country')]); !!}
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            {!! Form::label('landmark', __('business.landmark') . ':') !!}
            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-map-marker"></i>
              </span>
              {!! Form::text('landmark', null, ['class' => 'form-control',
              'placeholder' => __('business.landmark')]); !!}
            </div>
          </div>
        </div>
        <div class="@if(isset($quick_add)) hide @endif">
          <div class="clearfix"></div>
          <div class="col-md-12">
            <hr />
          </div>
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::label('custom_field1', __('lang_v1.custom_field', ['number' => 1]) . ':') !!}
              {!! Form::text('custom_field1', null, ['class' => 'form-control',
              'placeholder' => __('lang_v1.custom_field', ['number' => 1])]); !!}
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::label('custom_field2', __('lang_v1.custom_field', ['number' => 2]) . ':') !!}
              {!! Form::text('custom_field2', null, ['class' => 'form-control',
              'placeholder' => __('lang_v1.custom_field', ['number' => 2])]); !!}
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::label('custom_field3', __('lang_v1.custom_field', ['number' => 3]) . ':') !!}
              {!! Form::text('custom_field3', null, ['class' => 'form-control',
              'placeholder' => __('lang_v1.custom_field', ['number' => 3])]); !!}
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::label('custom_field4', __('lang_v1.custom_field', ['number' => 4]) . ':') !!}
              {!! Form::text('custom_field4', null, ['class' => 'form-control',
              'placeholder' => __('lang_v1.custom_field', ['number' => 4])]); !!}
            </div>
          </div>
        </div>
        <div class="clearfix"></div>

      </div>
    </div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>

    {!! Form::close() !!}

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script src="{{ asset('js/contact.js?v=' . $asset_v) }}"></script>
	