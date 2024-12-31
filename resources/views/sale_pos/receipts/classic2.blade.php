<div class="row">


	<!-- Logo -->
	@if(!empty($receipt_details->logo))
	<img src="{{$receipt_details->logo}}" class="img img-responsive center-block">
	@endif

	<!-- Header text -->
	@if(!empty($receipt_details->header_text))
	<div class="col-xs-12">
		{!! $receipt_details->header_text !!}
	</div>
	@endif

	<!-- business information here -->
	<div class="col-xs-12 text-center">
		<h2 class="text-center">
			<!-- Shop & Location Name  -->
			@if(!empty($receipt_details->display_name))
			{{$receipt_details->display_name}}
			@endif
		</h2>

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
			<!--
		@if(!empty($receipt_details->website))
			{{ $receipt_details->website }}
		@endif
        -->

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

		<!-- Title of receipt -->
		@if(!empty($receipt_details->invoice_heading))
		<h3 class="text-center">

			@if(!empty($receipt_details->cae))

			FACTURA
			{{$receipt_details->type_invoice}}
			@else

			Documento no valido como factura

			@endif
		</h3>

		@endif

		<!-- Invoice  number, Date  -->
		<p style="width: 100% !important" class="word-wrap">
			<span class="pull-left text-left word-wrap">
			    <b>Fecha</b> {{ $receipt_details->date}} <br>
				@if(!empty($receipt_details->invoice_no_prefix))
				<b>Factura N°</b>
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

				<b>{{ $receipt_details->tipo_doc }}</b> {{ $receipt_details->tax_number}} <br>
				@endif

				@if(!empty($receipt_details->customer_iva))
				<b>Condición IVA</b> {{ $receipt_details->customer_iva}} <br>
				@endif

				@if(!empty($receipt_details->customer_landmark))
				<b>Direccion</b> {{ $receipt_details->customer_landmark}} - {{ $receipt_details->customer_city}} - {{ $receipt_details->customer_state}} <br>
				@endif

				@if(!empty($receipt_details->customer_mobile))
				<b>Tel.</b> {{ $receipt_details->customer_mobile}} <br>
				@endif

				<!--
				@if(!empty($receipt_details->customer_info))
					{!! $receipt_details->customer_info !!}
				@endif
				@if(!empty($receipt_details->client_id_label))
					<br/>
					<b>{{ $receipt_details->client_id_label }}</b> {{ $receipt_details->client_id }}
				@endif
				@if(!empty($receipt_details->customer_tax_label))
					<br/>
					<b>{{ $receipt_details->customer_tax_label }}</b> {{ $receipt_details->customer_tax_number }}
				@endif
				@if(!empty($receipt_details->customer_custom_fields))
					<br/>{!! $receipt_details->customer_custom_fields !!}
				@endif
				@if(!empty($receipt_details->sales_person_label))
					<br/>
					<b>{{ $receipt_details->sales_person_label }}</b> {{ $receipt_details->sales_person }}
				@endif
				-->

			</span>

			<span class="pull-left text-left">





				@if(!empty($receipt_details->serial_no_label) || !empty($receipt_details->repair_serial_no))
				<br>
				@if(!empty($receipt_details->serial_no_label))
				<b>{!! $receipt_details->serial_no_label !!}</b>
				@endif
				{{$receipt_details->repair_serial_no}}<br>
				@endif
				@if(!empty($receipt_details->repair_status_label) || !empty($receipt_details->repair_status))
				@if(!empty($receipt_details->repair_status_label))
				<b>{!! $receipt_details->repair_status_label !!}</b>
				@endif
				{{$receipt_details->repair_status}}<br>
				@endif

				@if(!empty($receipt_details->repair_warranty_label) || !empty($receipt_details->repair_warranty))
				@if(!empty($receipt_details->repair_warranty_label))
				<b>{!! $receipt_details->repair_warranty_label !!}</b>
				@endif
				{{$receipt_details->repair_warranty}}
				<br>
				@endif

				<!-- Waiter info -->
				@if(!empty($receipt_details->service_staff_label) || !empty($receipt_details->service_staff))
				<br />
				@if(!empty($receipt_details->service_staff_label))
				<b>{!! $receipt_details->service_staff_label !!}</b>
				@endif
				{{$receipt_details->service_staff}}
				@endif
			</span>
		</p>
	</div>

	@if(!empty($receipt_details->defects_label) || !empty($receipt_details->repair_defects))
	<div class="col-xs-12">
		<br>
		@if(!empty($receipt_details->defects_label))
		<b>{!! $receipt_details->defects_label !!}</b>
		@endif
		{{$receipt_details->repair_defects}}
	</div>
	@endif
	<!-- /.col -->
</div>


<div class="row">
	<div class="col-xs-12">
		<br /><br />
		<table class="table table-responsive" style="width: 100%">
			<thead>
				<tr>
					<th>{{$receipt_details->table_product_label}}</th>
					<th>{{$receipt_details->table_qty_label}}</th>
					<th>{{$receipt_details->table_unit_price_label}}</th>
					<th>{{$receipt_details->table_subtotal_label}}</th>
				</tr>
			</thead>
			<tbody>
				@forelse($receipt_details->lines as $line)
				<tr>
					<td style="word-break: break-all;">
						@if(!empty($line['image']))
						<img src="{{$line['image']}}" alt="Image" width="50" style="float: left; margin-right: 8px;">
						@endif
						{{$line['name']}} {{$line['variation']}}
						@if(!empty($line['sub_sku'])), {{$line['sub_sku']}} @endif @if(!empty($line['brand'])), {{$line['brand']}} @endif @if(!empty($line['cat_code'])), {{$line['cat_code']}}@endif
						@if(!empty($line['product_custom_fields'])), {{$line['product_custom_fields']}} @endif
						@if(!empty($line['sell_line_note']))({{$line['sell_line_note']}}) @endif
						@if(!empty($line['lot_number']))<br> {{$line['lot_number_label']}}: {{$line['lot_number']}} @endif
						@if(!empty($line['product_expiry'])), {{$line['product_expiry_label']}}: {{$line['product_expiry']}} @endif
					</td>
					<td>{{$line['quantity']}} {{$line['units']}} </td>
					@if(!$receipt_details->type_invoice OR $receipt_details->type_invoice=='B')
					<td>{{$line['unit_price_inc_tax']}}</td>
					<td>{{$line['line_total']}}</td>
					@else

					<td>{{$line['unit_price_inc_tax']}}</td>

					<td>{{$line['line_total']}}</td>
					@endif

				</tr>
				@if(!empty($line['modifiers']))
				@foreach($line['modifiers'] as $modifier)
				<tr>
					<td>
						{{$modifier['name']}} {{$modifier['variation']}}
						@if(!empty($modifier['sub_sku'])), {{$modifier['sub_sku']}} @endif @if(!empty($modifier['cat_code'])), {{$modifier['cat_code']}}@endif
						@if(!empty($modifier['sell_line_note']))({{$modifier['sell_line_note']}}) @endif
					</td>
					<td>{{$modifier['quantity']}} {{$modifier['units']}} </td>
					<!--
					<td>{{$modifier['unit_price_inc_tax']}}</td>
					-->
					<td>{{$modifier['line_total']}}</td>
				</tr>
				@endforeach
				@endif
				@empty
				<tr>
					<td colspan="4">&nbsp;</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>




<div class="col-xs-12">
	<div class="table-responsive">
		<table class="table"  style="width: 100%">

			

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








			@if(!empty($receipt_details->payments))
			@foreach($receipt_details->payments as $payment)
			<tr>
				<th>{{$payment['method']}}</th>
				<td>{{$payment['amount']}}</td>

			</tr>
			@endforeach
			@endif




			<!-- Total Paid-->
			@if(!empty($receipt_details->total_paid))
			<tr>
				<th>
					Total Pagado
				</th>
				<td>
					{{$receipt_details->total_paid}}
				</td>
			</tr>
			@endif

			<!-- Total Due-->
			@if(!empty($receipt_details->total_due))
			<tr>
				<th>
					Total Debido:
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

				
				<!-- Total -->
				<tr>
					<th>
						{!! $receipt_details->total_label !!}
					</th>
					<td>
						{{$receipt_details->total}} 
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
</div>

@if($receipt_details->show_barcode)
<div class="row">
	<div class="col-xs-12">
		{{-- Barcode --}}
		<img class="center-block" src="data:image/png;base64,{{DNS1D::getBarcodePNG($receipt_details->invoice_no, 'C128', 2,30,array(39, 48, 54), true)}}">
	</div>
</div>
@endif

@if(!empty($receipt_details->footer_text))
<div class="row">
	<div class="col-xs-12">
		{!! $receipt_details->footer_text !!}
	</div>
</div>
@endif


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