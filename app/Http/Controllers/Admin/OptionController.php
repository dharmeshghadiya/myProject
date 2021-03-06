<?php

namespace App\Http\Controllers\Admin;

use App\Models\Language;
use App\Models\Option;
use App\Models\OptionTranslation;
use App\Models\VehicleOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            //DB::enableQueryLog();
            $options = Option::listsTranslations('name')
                ->select('options.id')
                ->get();
            // dd(DB::getQueryLog());
            return Datatables::of($options)
                ->addColumn('action', function($options){
                    $edit_button = '<a href="' . route('admin::option.edit', [$options->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $options->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    return '<div class="btn-icon-list">' . $edit_button . ' ' . $delete_button . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.option.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $languages = Language::all();
        return view('admin.option.create', ['languages' => $languages]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $id = $request->input('edit_value');

        if($id == NULL){
            $option_order = Option::max('id');
            $insert_id = Option::create([
                'vehicle_type_id' => 1,
                'option_order'    => $option_order + 1,
            ]);
            $languages = Language::all();
            foreach($languages as $language){
                OptionTranslation::create([
                    'name'      => $request->input($language->language_code . '_name'),
                    'option_id' => $insert_id->id,
                    'locale'    => $language->language_code,
                ]);
            }
            return response()->json(['success' => true, 'message' => trans('adminMessages.option_inserted')]);
        } else{
            $languages = Language::all();
            foreach($languages as $language){
                OptionTranslation::updateOrCreate([
                    'option_id' => $id,
                    'locale'    => $language->language_code,
                ],
                    [
                        'option_id' => $id,
                        'locale'    => $language->language_code,
                        'name'      => $request->input($language->language_code . '_name'),
                    ]);

            }
            return response()->json(['success' => true, 'message' => trans('adminMessages.option_updated')]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $option = Option::find($id);
        if($option){
            $languages = Language::all();
            return view('admin.option.edit', ['option' => $option, 'languages' => $languages]);
        } else{
            abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Option::where('id', $id)->delete();
        VehicleOption::where('option_id', $id)->delete();
        return response()->json(['success' => true, 'message' => trans('adminMessages.option_deleted')]);
    }
}
