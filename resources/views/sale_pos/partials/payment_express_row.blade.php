<div class="col-md-12">
	<div class="box box-solid payment_row bg-lightgray">

        @if(!empty($payment_line['id']))
        	{!! Form::hidden("payment[$row_index][payment_id]", $payment_line['id']); !!}
        @endif

		<div class="box-body" >
			<div class="row">
				<input type="hidden" class="payment_row_index" value="{{ $row_index}}">
				<div class="col-md-12">
					<div class="form-group">
						{!! Form::label("amount_$row_index" ,'Importe:*') !!}
						<div class="input-group">
							<span class="input-group-addon">
								<i class="fa fa-money"></i>
                            </span>
                            <input type="text" @if($is_credit_express) id="total-paying-credit" @else id="total-paying" @endif class="form-control payment-amount input_number" required placeholder="0.0">
						</div>
					</div>
					<div id="payment-message" class="alert alert-danger" style="display: none"></div>
				</div>

                    <div class="col-md-12">
                        <button type="button" class="btn btn-success btn-block btn-flat btn-lg no-print @if($pos_settings['disable_express_checkout'] != 0) hide @endif pos-express-btn pos-express-finalize-pay"
                        data-pay_method="cash"
                        title="Pagar en efectivo">
                            <div class="col-md-12 text-center">
                                <i class="fa fa-check" aria-hidden="true"></i>
                                <b>Pagar</b>
                            </div>
                        </button>
                    </div>
					{!! Form::hidden("payment[0][method_cash]", '', ['class' => 'form-control ',  'id' => "method_cash_0", 'style']); !!}
					{!! Form::hidden("payment[0][method_card]", '', ['class' => 'form-control ', 'required', 'id' => "method_card_0", 'style']); !!}
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>