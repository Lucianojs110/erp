<div class="pos-tab-content">
    <div class="row">
      




        <div class="col-sm-4">
            <div class="form-group">
                <b>Limite de facturación: </b><br>
                Para pagos con tarjeta:
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-credit-card"></i>
                    </span>
                    {!! Form::text('invoice_limit_card', $business->invoice_limit_card, ['class' => 'form-control']); !!}
                </div>
                Para otros medios de pago
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-money"></i>
                    </span>
                    {!! Form::text('invoice_register_limit', $business->invoice_register_limit, ['class' => 'form-control']); !!}
                </div>
                
            </div>
        </div>


        
        <div class="col-sm-4">
            <div class="form-group">
                <div class="checkbox">
                <br>
                  <label>
                    {!! Form::checkbox('enable_inline_tax', 1, $business->enable_inline_tax , 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.enable_inline_tax' ) }}
                  </label>
                </div>
            </div>
        </div>


       
       
     
    </div>
</div>