<div class="modal fade" tabindex="-1" role="dialog" id="product_modal">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Agregar Producto Personalizado</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							{!! Form::label("product_name",'Nombre del producto') !!}
							{!! Form::text("", null, ['class' => 'form-control', 'placeholder' => '', 'id' => "custom_product_name"]); !!}
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							{!! Form::label("sell_price", 'Precio de venta') !!}
							{!! Form::number("", null, ['class' => 'form-control', 'placeholder' => 'precio', 'id' => 'custom_product_sell_price', 'min' =>'1']); !!}
						</div>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<div id="card-payment-message" class="alert alert-danger" style="display: none"></div>
				<button type="button" class="btn btn-primary btn-lg btn-block" id="pos-save-custom-product" data-pay_method="card">Agregar a la venta</button>
			</div>

		</div>
	</div>
</div>