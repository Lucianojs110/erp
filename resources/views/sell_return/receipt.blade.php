<!-- business information here -->
<div class="col-xs-12 text-center">


	<!-- Address -->
	<p>
		@if(!empty($receipt_details->address))
		<small class="text-center">
			{!! $receipt_details->address !!}
		</small>
		@endif
		@if(!empty($receipt_details->contact))
		<br />{{ $receipt_details->contact }}
		@endif
		@if(!empty($receipt_details->contact) && !empty($receipt_details->website))
		,
		@endif


		@if(!empty($receipt_details->location_custom_fields))
		<br>Telefono
		@endif
	</p>
	<p>
		@if(!empty($receipt_details->sub_heading_line1))
		{{ $receipt_details->sub_heading_line1 }}
		@endif
		@if(!empty($receipt_details->sub_heading_line2))
		<br>{{ $receipt_details->sub_heading_line2 }}
		@endif
		@if(!empty($receipt_details->sub_heading_line3))
		<br>{{ $receipt_details->sub_heading_line3 }}
		@endif
		@if(!empty($receipt_details->sub_heading_line4))
		<br>{{ $receipt_details->sub_heading_line4 }}
		@endif
		@if(!empty($receipt_details->sub_heading_line5))
		<br>{{ $receipt_details->sub_heading_line5 }}
		@endif
	</p>
	<p>
		@if(!empty($receipt_details->tax_info1))
		<!--cambio tax label porque guardo el codigo de impuestos -->
		<!--<b>{{ $receipt_details->tax_label1 }}</b>--> <b>Cuit</b> {{ $receipt_details->tax_info1 }}
		@endif

		@if(!empty($receipt_details->tax_info2))
		<b>{{ $receipt_details->tax_label2 }}</b> {{ $receipt_details->tax_info2 }}
		@endif
	</p>

</div>
<!-- business information here -->
<div class="row invoice-info">

	<h3 class="text-center">

		@if(!empty($receipt_details->cae))
		Nota de Credito
		{{$receipt_details->type_invoice}}
		@else
		Nota de credito <br>
		<p style="font-size: 15px !important" class="word-wrap">Documento Sin valor fiscal </p>

		@endif
	</h3>
</div>


<!-- Invoice  number, Date  -->
<p style="width: 100% !important" class="word-wrap">
	<span class="pull-left text-left word-wrap">
		@if(!empty($receipt_details->num_invoice_afip))
		<b>Comprobante N°</b>
		@endif

		@if($receipt_details->num_invoice_afip)
		{{$receipt_details->num_invoice_afip}}
		@else
		{{$receipt_details->invoice_no}}
		@endif

		<!-- Table information-->
		@if(!empty($receipt_details->table_label) || !empty($receipt_details->table))
		<br />
		<span class="pull-left text-left">
			@if(!empty($receipt_details->table_label))
			<b>{!! $receipt_details->table_label !!}</b>
			@endif
			{{$receipt_details->table}}

			<!-- Waiter info -->
		</span>
		@endif





		<!-- customer info -->
		@if(!empty($receipt_details->customer_name))
		<br />
		<b>Cliente</b> {{ $receipt_details->customer_name }} <br>
		@endif

		@if(!empty($receipt_details->tax_number != 0))

		<b>Cuit</b> {{ $receipt_details->tax_number}} <br>
		@endif


		@if($receipt_details->afip_invoice_date)
		<b>Fecha </b> {{ $newDate = date("d-m-Y", strtotime($receipt_details->afip_invoice_date)) }}<br>
		@else
		<b>Fecha</b> {{ $newDate = date("d-m-Y", strtotime($receipt_details->invoice_date)) }}<br>
		@endif
		<b>Comprobante Referencia N°: </b>
		{{ $receipt_details->ref_no}}


	</span>


<div class="row">
	<div class="col-xs-12">
		<br />
		<table class="table table-responsive" style="width: 100%">
			<thead>
				<tr>
					<td>No</td>
					@php
					$p_width = 35;
					@endphp
					@if($receipt_details->show_cat_code != 1)
					@php
					$p_width = 45;
					@endphp
					@endif
					<td>
						{{$receipt_details->table_product_label}}
					</td>
					@if($receipt_details->show_cat_code == 1)
					<td>{{$receipt_details->cat_code_label}}</td>
					@endif

					<td>
						{{$receipt_details->table_qty_label}}
					</td>
					<td>
						{{$receipt_details->table_unit_price_label}}
					</td>
					<td>
						{{$receipt_details->table_subtotal_label}}
					</td>
				</tr>
			</thead>
			<tbody>
				@foreach($receipt_details->lines as $line)
				<tr>
					<td>
						{{$loop->iteration}}
					</td>
					<td>
						{{$line['name']}} {{$line['variation']}}
						@if(!empty($line['sub_sku'])), {{$line['sub_sku']}} @endif @if(!empty($line['brand'])), {{$line['brand']}} @endif
						@if(!empty($line['sell_line_note']))({{$line['sell_line_note']}}) @endif
					</td>

					@if($receipt_details->show_cat_code == 1)
					<td>
						@if(!empty($line['cat_code']))
						{{$line['cat_code']}}
						@endif
					</td>
					@endif

					<td>
						{{$line['quantity']}} {{$line['units']}}
					</td>
					@if(!$receipt_details->type_invoice OR $receipt_details->type_invoice=='B')
					<td>{{$line['unit_price']}}</td>
					<td>{{$line['line_total']}}</td>
					@else
					<td>{{$line['unit_price_inc_tax']}}</td>
					<td>{{$line['line_total']}}</td>
					@endif
				</tr>
				@endforeach


			</tbody>
		</table>
	</div>
</div>

<div class="col-xs-12">
	<div class="table-responsive">
		<table class="table" style="width: 100%">

		

		

			<!-- Total Due-->
			@if(!empty($receipt_details->total_due))
			<tr>
				<th>
						{!! $receipt_details->total_due_label !!}
				</th>
				<td>
					{{$receipt_details->total_due}}
				</td>
			</tr>
			@endif

			@if(!empty($receipt_details->all_due))
			<tr>
				<th>
					{!! $receipt_details->all_bal_label !!}
				</th>
				<td>
					{{$receipt_details->all_due}}
				</td>
			</tr>
			@endif



			@if( $receipt_details->type_invoice=='A')
			<tr>
				<th>
					{!! $receipt_details->subtotal_label !!}
				</th>
				<td>
					{{$receipt_details->subtotal}}
				</td>
			</tr>
			@endif




			<!-- Shipping Charges -->
			@if(!empty($receipt_details->shipping_charges))
			<tr>
				<th>
					{!! $receipt_details->shipping_charges_label !!}
				</th>
				<td>
					{{$receipt_details->shipping_charges}}
				</td>
			</tr>
			@endif

			<!-- Discount -->
			@if( !empty($receipt_details->discount) )
			<tr>
				<th>
					Descuento
				</th>

				<td>
					(-) {{$receipt_details->discount}}
				</td>
			</tr>
			@endif

		<!-- Tax -->
		@if(!empty($receipt_details->cae))
				@if($receipt_details->tax_id=='6')
					<th>
						IIBB (3.3%)
					</th>
					<td>
						(+) {{$receipt_details->iibb}}
					</td>

				</tr>
				@endif
				@if( $receipt_details->type_invoice=='A')

				<tr>
					<th>
						Importe Neto
					</th>

					<td>
					$ {{$receipt_details->neto}}
					</td>
				</tr>

				
				@if( $receipt_details->iva21 > 0)
				<tr>
					<th>
						IVA (21%)
					</th>

					<td>
						(+) {{$receipt_details->iva21}}
					</td>
				</tr>
				@endif
				@if( $receipt_details->iva10 > 0)
				<tr>
					<th>
						IVA (10.5%)
					</th>
					<td>
						(+) {{$receipt_details->iva10}}
					</td>
				</tr>
				@endif
				@endif
			
				</tr>
			
				@endif


			<!-- Total -->
			<tr>
				<th>
					Total:
				</th>
				<td>
					{{$receipt_details->total}}
				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>


</table>

@if(!empty($receipt_details->cae))
<div class="row">
	<div class="col-xs-12">
		<b>CAE:</b> {!! $receipt_details->cae !!} <br>
		<b>Vto:</b> {{ $newDate = date("d-m-Y", strtotime($receipt_details->exp_cae)) }}
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl={!! $receipt_details->qrCode!!}">
	</div>
</div>
@endif