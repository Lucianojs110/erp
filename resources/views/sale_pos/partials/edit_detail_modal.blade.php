<!-- Edit Shipping Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="posDetailTransactionModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Detalles de la transacción</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
				        <div class="form-group">
				            {!! Form::label('transaction_details_modal', 'Detalle de la transacción' . ':*' ) !!}
				            {!! Form::textarea('transaction_details_modal', null, ['class' => 'form-control','placeholder' => 'Detalle de la transacción', 'required' ,'rows' => '2']); !!}
				        </div>
				    </div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="posDetailTransactionModalUpdate">@lang('messages.update')</button>
			    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.cancel')</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->