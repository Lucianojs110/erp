<div class="modal fade" tabindex="-1" role="dialog" id="modal_express_payment">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Pago Efectivo</h4>
			</div>
			<div class="modal-body">
				<div class="row">
                    <div class="col-md-6">
                        <div class="row">
							<div id="payment_rows_div">
								<div class="col-md-12">
									<div class="form-group">
										{!! Form::label("amount_0" ,'Importe en Pesos ARS:*') !!}
										<div class="input-group">
											<span class="input-group-addon">
												<i class="fa fa-money"></i>
											</span>
											{!! Form::text("payment_amount", '', ['class' => 'form-control payment-amount input_number', 'required', 'id' => "total-paying", 'placeholder' => '0.0']); !!}
										</div>
										<input type="hidden" name="change_returned" id="change_returned">
										<input type="hidden" name="change_return" id="change_return">
										
									</div>
									<div id="payment-message" class="alert alert-danger" style="display: none"></div>
								</div>

								@if($pos_settings['pagos_monedas']=='0')
								    <input class="form-control" type="hidden" name="payment[0][guarani]" id="guarani" placeholder="0.00">
									<input class="form-control" type="hidden" name="payment[0][reales]" id="real" placeholder="0.00">
									<input class="form-control" type="hidden" name="payment[0][euro]" id="dolar" placeholder="0.00">
									<input class="form-control" type="hidden" name="payment[0][dolar]" id="euro" placeholder="0.00">
								@endif

								@if($pos_settings['pagos_monedas']=='1')
								<div class="col-md-12">
									<div class="form-group">
										Importe en Guaraníes
										<div class="input-group">
											<span class="input-group-addon">
												<i class="fa fa-money"></i>
											</span>
											<input class="form-control" type="number" name="payment[0][guarani]" id="guarani" placeholder="0.00">
										</div>
									</div>
								</div>
								

								<div class="col-md-12">
									<div class="form-group">
										Importe en Reales
										<div class="input-group">
											<span class="input-group-addon">
												<i class="fa fa-money"></i>
											</span>
											<input class="form-control" type="number" name="payment[0][reales]" id="real" placeholder="0.00">
										</div>
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										Importe en Dólares
										<div class="input-group">
											<span class="input-group-addon">
												<i class="fa fa-money"></i>
											</span>
											<input class="form-control" type="number" name="payment[0][dolar]" id="dolar" placeholder="0.00">
										</div>
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										Importe en Euros
										<div class="input-group">
											<span class="input-group-addon">
												<i class="fa fa-money"></i>
											</span>
											<input class="form-control" type="number" name="payment[0][euro]" id="euro" placeholder="0.00">
										</div>
									</div>
								</div>
								@endif

								{!! Form::hidden("payment[0][method_cash]", '', ['class' => 'form-control ',  'id' => "method_cash_0", 'style']); !!}
								{!! Form::hidden("payment[0][method_card]", '', ['class' => 'form-control ',  'id' => "method_card_0", 'style']); !!}
								<div class="col-md-12">
									{{-- boton para venta efectivo --}}
									<button type="button" class="btn btn-success btn-block btn-flat btn-lg no-print @if($pos_settings['disable_express_checkout'] != 0) hide @endif pos-express-btn pos-express-finalize-pay"
									data-pay_method="cash"
									title="Pagar en efectivo">
										<div class="col-md-12 text-center">
											<i class="fa fa-check" aria-hidden="true"></i>
											<b>Pagar</b>
										</div>
									</button>
								</div>
							</div>
                        </div>
                    </div>
                    <div class="col-md-6">
						<div class="box box-solid bg-orange">
				            <div class="box-body">
				            	<div class="col-md-6">
				            		<hr>
				            		<strong>
				            			Total a pagar:
				            		</strong>
				            		<br/>
				            		<span class="lead text-bold total_payable_span">0</span>
				            	</div>

				            	<div class="col-md-6">
				            		<hr>
				            		<strong>
				            			Pago total:
				            		</strong>
				            		<br/>
				            		<span class="lead text-bold total_paying">0</span>
				            		<input type="hidden" id="total_paying_input">
				            	</div>

				            	<div class="col-md-6">
				            		<hr>
				            		<strong>
				            			Vuelto:
				            		</strong>
				            		<br/>
				            		<span class="lead text-bold change_return_span">0</span>
				            		
				            		@if(!empty($change_return['id']))
				                		<input type="hidden" name="change_return_id" 
				                		value="{{$change_return['id']}}">
				                	@endif
				            	</div>            					              
				            </div>
				          </div>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>