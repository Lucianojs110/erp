<div class="modal-dialog" role="document">
        <div class="modal-content">

                {!! Form::model($delivery, ['url' => action('DeliveryController@store'), 'method' => 'post', 'id' => 'delivery_edit_form' ]) !!}

                <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">@lang( 'lang_v1.add_delivery' )</h4>
                </div>

                <div class="modal-body">
                        {{-- agent name --}}
                        <div class="form-group">
                                <p> Vendedor: {{ $agent->first_name . ' ' . $agent->last_name }} </p>
                        </div>
                        {{-- select for business locations --}}
                        <div class="form-group">
                                {!! Form::label('location_id', __( 'business.business_location' ) . ':*') !!}
                                {!! Form::select('location_id', $business_locations, $delivery->business_location_id, ['class' => 'form-control select2', 'required', 'placeholder' => __( 'messages.please_select' ) ]) !!}
                        </div>
                        {{-- select for customers --}}
                        <div class="form-group">
                                {!! Form::label('contact_id', __( 'contact.customer' ) . ':*') !!}
                                {!! Form::select('contact_id', $customers, $delivery->contact_id, ['class' => 'form-control select2', 'required', 'placeholder' => __( 'messages.please_select' ) ]); !!}
                        </div>
                        {{-- insert select with search for products and quantity --}}
                        <div class="form-group">
                                {!! Form::label('product', __( 'product.add_new_product' ) . ':*') !!}
                                <select name="product" class="form-control select2" required>
                                        <option value="" disabled selected>@lang('messages.please_select')</option>
                                        @foreach($products as $product)
                                        <option data-vid="{{$product->v_id}}" data-price="{{$product->price}}" value="{{ $product->p_id }}">{{ $product->product_name }}</option>
                                        @endforeach
                                </select>
                        </div>
                        <button type="button" class="btn btn-primary" id="add_delivery_product">@lang('messages.add_product')</button>
                </div>

                <div class="table-responsive">
                        <table class="table table-bordered table-striped delivery_products_table" id="product_table">
                                <thead>
                                        <tr>
                                                <th>@lang('product.product_name')</th>
                                                <th>@lang('sale.qty')</th>
                                                <th>@lang('sale.unit_price')</th>
                                                <th>@lang('sale.subtotal')</th>
                                                <th>@lang('messages.action')</th>
                                        </tr>
                                </thead>
                                <tbody>
                                        @foreach($delivery->deliveryDetails as $delivery_detail)
                                                @php 
                                                        $variation = \App\Variation::find($delivery_detail->variation_id);
                                                @endphp
                                        <tr>
                                                <td>
                                                        <input type="hidden" name="product_id[]" value="{{ $delivery_detail->product->id }}">
                                                        {{ $delivery_detail->product->name }}
                                                </td>
                                                <td>
                                                        <input type="number" name="quantity[]" class="form-control input-sm" value="{{ $delivery_detail->quantity }}" required>
                                                </td>
                                                <td>
                                                        <input type="number" name="unit_price[]" class="form-control input-sm" value="{{ $variation->sell_price_inc_tax}}" required>
                                                </td>
                                                <td>
                                                        <input type="number" name="subtotal[]" class="form-control input-sm" value="{{ $variation->sell_price_inc_tax * $delivery_detail->quantity }}" required>
                                                </td>
                                                <td>
                                                        <button type="button" class="btn btn-danger btn-xs delete_product" title="@lang('messages.delete')">
                                                                <i class="fa fa-trash"></i>
                                                        </button>
                                                </td>
                                        </tr>
                                        @endforeach

                                </tbody>
                        </table>
                </div>

                <div class="row">
                        <div class="col-md-6">
                                <div class="form-group">
                                        {!! Form::label('delivery_date', __( 'lang_v1.delivery_date' ) . ':*') !!}
                                        {!! Form::date('delivery_date', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'lang_v1.delivery_date' )]); !!}
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