<!-- Edit Order tax Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="posEditOrderTaxModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">@lang('sale.edit_order_tax')</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
				        <div class="form-group">
				            {!! Form::label('order_tax_modal', __('sale.order_tax') . ':*' ) !!}
				            <div class="input-group">
				                <span class="input-group-addon">
				                    <i class="fa fa-info"></i>
				                </span>
				                {!! Form::select('order_tax_modal', $taxes['tax_rates'], $selected_tax, ['placeholder' => __('messages.please_select'), 'class' => 'form-control'], $taxes['attributes']); !!}
				            </div>
				        </div>
				    </div>
				</div>

				@php
					$withholdingTypes = [
						1 => 'Percepción',
						2 => 'Retención',
					];

					$withholdingsJson = isset($withholdings) ? json_encode($withholdings) : '[]';
				@endphp

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							{!! Form::label('selected_withholding_type', 'Seleccionar Categoría:') !!}
							<select name="selected_withholding_type" id="selected_withholding_type" class="form-control" onchange="showWithholdingOptions()">
								<option value="0" selected>Seleccionar categoría</option>
								@foreach($withholdingTypes as $key => $type)
									<option value="{{ $key }}">{{ $type }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							{!! Form::label('selected_withholdings', 'Seleccionar Percepciones/Retenciones:') !!}
							{!! Form::select('selected_withholdings[]', [], null, ['class' => 'form-control select2', 'multiple', 'id' => 'selected_withholdings']); !!}
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="posEditOrderTaxModalUpdate">@lang('messages.update')</button>
			    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.cancel')</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
	var withholdings = <?php echo $withholdingsJson ?>;

	function showWithholdingOptions() {
		$("#withholding-options-td").hide();
		$("#selected_withholdings").empty();

		var selectedWithholdingType = $("#selected_withholding_type").val();

		if (selectedWithholdingType === '1' || selectedWithholdingType === '2') {
			$("#withholding-options-td").show();
			updateWithholdingOptions(selectedWithholdingType);
		}
	}

	function updateWithholdingOptions(selectedWithholdingType) {
		var options = [];

		for (var i = 0; i < withholdings.length; i++) {
			var withholding = withholdings[i];
			if (withholding.type == selectedWithholdingType) {
				options.push({
					id: withholding.id,
					text: withholding.name + " " + withholding.percentage + "%"
				});
			}
		}

		$("#selected_withholdings").select2({
			data: options
		});
	}

	$(document).ready(function () {
		showWithholdingOptions();
	});
</script>