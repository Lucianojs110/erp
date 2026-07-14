<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;
use App\Brands;
use App\Category;
use App\TaxRate;
use App\Unit;

class NewProductImport implements WithHeadingRow, WithValidation
{
    public $categoria;

    protected function validateOrCreateBrand($brand_name)
    {
        $brand = Brands::firstOrCreate(
            ['name' => $brand_name],
            [
                'business_id' => session()->get('user.business_id'),
                'created_by' => auth()->user()->id
            ]
        );

        return $brand->id;
    }

    protected function validateOrCreateCategory($category_name, $parent_id)
    {
        $category = Category::firstOrCreate(
            ['name' => $category_name, 'parent_id' => $parent_id],
            [
                'business_id' => session()->get('user.business_id'),
                'created_by' => auth()->user()->id
            ]
        );

        if($parent_id == 0) {
            $this->categoria = $category->id;
        }

        return $category->id;
    }

    protected function validateUnit($unit_name)
    {
        $unit = Unit::where(function ($query) use ($unit_name) {
            $query->where('short_name', $unit_name)
                  ->orWhere('actual_name', $unit_name);
        })->first();
        
        if (!$unit) {
            return false;
        }

        return $unit->id;
    }

    protected function validateTax($tax_name)
    {
        $tax = TaxRate::where('name', $tax_name)->first();
        
        if (!$tax) {
            return false;
        }

        return $tax->id;
    }

    protected function validateExplode($value, $delimiter)
    {
        return array_map('trim', explode( $delimiter, $value ));
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'no_empty_spaces'],
            'marca' => ['required', 'no_empty_spaces', function ($attribute, $value, $fail) {
                $this->validateOrCreateBrand($value);
            }],
            'unidad' => ['required', 'no_empty_spaces', function ($attribute, $value, $fail) {
                $this->validateUnit($value);
            }],
            'categoria' => ['required', 'no_empty_spaces', function ($attribute, $value, $fail) {
                $this->validateOrCreateCategory($value, 0);
            }],
            'sub_categoraa' => ['required', 'no_empty_spaces', function ($attribute, $value, $fail) {
                $this->validateOrCreateCategory($value, $this->categoria);
            }],
            'sku_codigo_barra' => ['required', 'no_empty_spaces'],
            'barcode_type' => ['nullable', 'no_empty_spaces', Rule::in(['C128', 'C39', 'EAN13', 'EAN8', 'UPCA', 'UPCE'])],
            'administrar_stock_1yes_0no' => ['required', 'no_empty_spaces', Rule::in([0, 1])],
            'cantidad_alerta' => ['required', 'no_empty_spaces'],
            'expires_in' => ['nullable', 'numeric'],
            'expiry_period_unit_monthsdays' => ['nullable', Rule::in(['months', 'days'])],
            'applicable_tax' => ['nullable', function ($attribute, $value, $fail) {
                $this->validateTax($value);
            }],
            'selling_price_tax_type_inclusive_or_exclusive' => ['required', 'no_empty_spaces', Rule::in(['Inclusive', 'Exclusive'])],
            'tipo_producto_individual_o_variable' => ['required', 'no_empty_spaces', Rule::in(['Single', 'Variable'])],
            'variation_name_keep_blank_if_product_type_is_single' => ['nullable', 'no_empty_spaces'],
            'variation_values_seperated_values_blank_if_product_type_if_single' => ['nullable', 'no_empty_spaces', 'string', function ($attribute, $value, $fail) {
                $this->validateExplode($value, '|');
            }],
            'precio_compra_incluyendo_impuesto' => ['required', 'numeric'],
            'precio_compra_excluyendo_impuesto' => ['required', 'numeric'],
            'ganancia' => ['nullable', 'numeric'],
            'precio_de_venta' => ['required', 'numeric'],
            'stock_inicial' => ['nullable', 'numeric'],
            'local_en_donde_esta_la_mercaderaa' => ['nullable', 'no_empty_spaces', Rule::exists('business,name')],
            'expiry_date' => ['nullable', 'date'],
            'enable_imei_or_serial_number1yes0no' => ['nullable', Rule::in([0, 1])],
            'weight' => ['nullable', 'numeric'],
            'rack' => ['nullable', 'string'],
            'row' => ['nullable', 'string'],
            'position' => ['nullable', 'string'],
            'imagen' => ['nullable', 'string'],
            'descripcion' => ['nullable', 'string'],
            'grupo_de_precio_1' => ['nullable', Rule::exists('selling_price_groups,name')],
            'precio_1' => ['nullable', 'numeric'],
            'grupo_de_precio_2' => ['nullable', Rule::exists('selling_price_groups,name')],
            'precio_2' => ['nullable', 'numeric'],
            'campo_personalizado1' => ['nullable', 'string'],
            'campo_personalizado2' => ['nullable', 'string'],
            'campo_personalizado3' => ['nullable', 'string'],
            'campo_personalizado4' => ['nullable', 'string'],
        ];
    }

    public function collection(Collection $collection)
    {
        $results = [];
        $errors = []; 
        $currentRow = 0;
        $rows = $collection->first();

        foreach ($rows as $data) {
            $currentRow++;

            $validator = Validator::make($data->toArray(), $this->rules());

            if ($validator->fails()) {
                $errors[] = [
                    'fila' => $currentRow,
                    'errores' => $validator->errors()->all(),
                ];
                continue;
            }

            $results[] = [
                'name' => $data['name'],
                'brand_id' => $data['brand_id'],
                'unit_id' => $data['unit_id'],
                'category_id' => $data['category_id'],
                'sub_category_id' => $data['sub_category_id'],
                'sku' => $data['sku'],
                'barcode_type' => $data['barcode_type'],
                'enable_stock' => $data['enable_stock'],
                'alert_quantity' => $data['alert_quantity'],
                'expiry_period' => $data['expiry_period'],
                'expiry_period_type' => $data['expiry_period_type'],
                'tax' => $data['tax'],
                'tax_type' => $data['tax_type'],
                'type' => $data['type'],
                'variation_name' => $data['variation_name'],
                'variation_values' => $data['variation_values'],
                'dpp_inc_tax' => $data['dpp_inc_tax'],
                'dpp_exc_tax' => $data['dpp_exc_tax'],
                'profit_margin' => $data['profit_margin'],
                'selling_price' => $data['selling_price'],
                'variation_selling_price' => $data['variation_selling_price'],
                'business_id' => $data['business_id'],
                'stock_initial' => $data['stock_initial'],
                'exp_date' => $data['exp_date'],
                'enable_sr_no' => $data['enable_sr_no'],
                'weight' => $data['weight'],
                'rack' => $data['rack'],
                'row' => $data['row'],
                'position' => $data['position'],
                'imagen' => $data['imagen'],
                'product_description' => $data['product_description'],
                'selling_price_group' => $data['selling_price_group'],
                'card_group_price' => $data['card_group_price'],
                'transfer_group' => $data['transfer_group'],
                'transfer_group_price' => $data['transfer_group_price'],
                'product_custom_field1' => $data['product_custom_field1'],
                'product_custom_field2' => $data['product_custom_field2'],
                'product_custom_field3' => $data['product_custom_field3'],
                'product_custom_field4' => $data['product_custom_field4'],
            ];
        }

        dd($errors);

        return [
            'results' => $results,
            'errors' => $errors,
        ];
    }
}
