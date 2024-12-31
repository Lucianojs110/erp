<div class="modal-dialog" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('DeliveryController@store'), 'method' => 'post', 'id' => 'delivery_add_form' ]) !!}



        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang( 'lang_v1.add_delivery' )</h4>
        </div>

        <div class="modal-body">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="user_id">@lang('lang_v1.sales_commission_agent'):</label>
                        <select name="user_id" class="form-control select2" required>
                            <option value="" disabled selected>@lang('messages.please_select')</option>
                            @foreach($agents as $agent)
                            <option data-commission="{{$agent->commission_percentage}}" value="{{ $agent->id }}">{{ $agent->full_name }} ({{ $agent->commission_percentage * 100 }}% com.)</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    {{-- select for business locations --}}
                    <div class="form-group">
                        {!! Form::label('location_id', __( 'business.business_location' ) . ':*') !!}
                        {!! Form::select('location_id', $business_locations, null, ['class' => 'form-control select2', 'required', 'placeholder' => __( 'messages.please_select' ) ]); !!}
                    </div>
                </div>
            </div>


            {{-- select for customers --}}
            <div class="form-group">
                {!! Form::label('contact_id', __( 'contact.customer' ) . ':*') !!}
                {!! Form::select('contact_id', $customers, null, ['class' => 'form-control select2', 'required', 'placeholder' => __( 'messages.please_select' ) ]); !!}
            </div>

            {{-- insert select with search for products and quantity --}}
            <div class="form-group">
                {!! Form::label('product', __( 'product.add_new_product' ) . ':*') !!}
                <select disabled name="product" class="form-control select2" required>
                    <option value="" disabled selected>@lang('messages.please_select')</option>
                    @foreach($products as $product)
                    <option data-vid="{{$product->v_id}}" data-lid="{{$product->l_id}}" data-price="{{$product->price}}" value="{{ $product->p_id }}">{{ $product->product_name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="button" class="btn btn-primary" id="add_delivery_product">@lang('messages.add')</button>

            <div class="table-responsive">
                <table class="table table-bordered table-striped delivery_products_table" id="delivery_products_table">
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

                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group
            ">
                        {!! Form::label('delivery_date', __( 'messages.date' ) . ':*') !!}
                        {!! Form::date('delivery_date', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'lang_v1.delivery_date' )]); !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('commission_amount', 'Comision de venta'. ':') !!}
                        {!! Form::number('commission_amount', 0, ['class' => 'form-control input_number', 'readonly', 'step' => '0.01']); !!}
                  
                    </div>
                </div>
            </div>

            {{-- show total for all products and quantities --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('total_commission', __( 'lang_v1.cmmsn_percent' ) . ':') !!}
                        {!! Form::number('total_commission', 0, ['class' => 'form-control input_number', 'readonly', 'step' => '0.01']); !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('total', __( 'sale.total_amount' ) . ':') !!}
                        {!! Form::number('total', 0, ['class' => 'form-control input_number', 'readonly', 'step' => '0.01']); !!}
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