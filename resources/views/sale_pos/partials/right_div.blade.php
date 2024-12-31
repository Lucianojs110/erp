@if($pos_settings['hide_product_suggestion'] == 0)
	@if(!isset($has_delivery) || $has_delivery == false) 
		@include('sale_pos.partials.product_list_box')
	@endif
@endif

@if($pos_settings['hide_recent_trans'] == 0)
	@include('sale_pos.partials.recent_transactions_box')
@endif