<?php

namespace App\Http\Controllers;

use App\Withholding;
use Illuminate\Http\Request;
use Datatables;

class WithholdingController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $withholdings = Withholding::where('business_id', $business_id);

            return Datatables::of($withholdings)
                ->addColumn(
                    'action',
                    '@can("tax_rate.update")
                    <button data-href="{{action(\'WithholdingController@edit\', [$id])}}" class="btn btn-xs btn-primary edit_withholdings_button" data-container=".withholdings_modal">
                    <i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</button>
                        &nbsp;
                    @endcan
                    @can("tax_rate.delete")
                        <button data-href="{{action(\'WithholdingController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_withholdings_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                    @endcan'
                )
                ->removeColumn('id')
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create()
    {
        return view('withholdings.create');
    }

    public function store(Request $request)
    {
        try {
            $business_id = request()->session()->get('user.business_id');

            Withholding::create([
                'name' => $request->input('name'),
                'business_id' => $business_id,
                'percentage' => $request->input('percentage'),
                'type' => $request->input('type'),
            ]);

            $output = ['success' => true,
                            'msg' => __("Retenci o percepción agregada correctamente")
                        ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => false,
                            'msg' => __("messages.something_went_wrong")
                        ];
        }

        return $output;
    }

    public function show(Withholding $id)
    {

    }

    public function edit($id)
    {
        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $withholdings = Withholding::find($id);

            return view('withholdings.edit')
                ->with(compact('withholdings'));
        }
    }

    public function update(Request $request, $id)
    {
        if (request()->ajax()) {
            try {
                $business_id = request()->session()->get('user.business_id');

                $withholdings = Withholding::findOrFail($id);
                $withholdings->name = $request->input('name');
                $withholdings->business_id = $business_id;
                $withholdings->percentage = $request->input('percentage');
                $withholdings->type = $request->input('type');
                $withholdings->save();

                $output = ['success' => true,
                            'msg' => "Percepción o retención actualizado correctamente"
                            ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
                $output = ['success' => false,
                            'msg' => __("messages.something_went_wrong")
                        ];
            }

            return $output;
        }
    }

    public function destroy($id)
    {
        if (request()->ajax()) {
            try {
                Withholding::findOrFail($id)->delete();

                $output = ['success' => true,
                            'msg' => "Percepción o retención eliminado correctamente"
                            ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
                $output = ['success' => false,
                            'msg' => __("messages.something_went_wrong")
                        ];
            }

            return $output;
        }
    }
}
