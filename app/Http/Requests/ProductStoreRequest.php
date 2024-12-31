<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => "required|string",
            "brand_id" => "nullable|exists:brands,id",
            "unit_id" => "required|exists:units,id",
            "category_id" => "nullable|exists:categories,id",
            "sub_category_id" => "nullable|exists:categories,id",
            "sku" => "nullable|string",
            "barcode_type" => "required",
            "enable_stock" => "nullable",
            "alert_quantity" => "required_if:enable_stock,1",
            "product_description" => "nullable|string",
            "expiry_period" => "nullable",
            "expiry_period_type" => "nullable|in:months,years,days",
            "enable_sr_no" => "nullable",
            "weight" => "nullable",
            "product_custom_field1" => "nullable|string",
            "product_custom_field2" => "nullable|string",
            "product_custom_field3" => "nullable|string",
            "product_custom_field4" => "nullable|string",
            "tax" => "nullable|exists:tax_rates,id",
            "tax_type" => "nullable|string",
            "type" => "nullable|string",
            "hasMayorista" => "nullable",
            "single_dpp" => "required",
            "single_dpp_inc_tax" => "required",
            "profit_percent" => "required",
            "single_dsp" => "nullable",
            "single_dsp_inc_tax" => "nullable",
            "cantidadMayorista" => "nullable",
            "precioMayorista" => "nullable",
            "submit_type" => "required:in:submit_n_add_opening_stock,submit_n_add_selling_prices,save_n_add_another,submit",
            "image" => 'nullable|image|mimes:jpeg,png,gif'
        ];
    }
}
