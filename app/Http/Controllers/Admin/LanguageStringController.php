<?php

namespace App\Http\Controllers\Admin;

use App\LanguageString;
use App\LanguageStringTranslation;
use App\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class LanguageStringController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if($request->ajax()){

            $language_strings = LanguageString::listsTranslations('name')
                ->select('language_strings.id', 'language_strings.screen_name', 'language_strings.name_key', 'language_strings.app_or_panel')
                ->get();

            return Datatables::of($language_strings)
                ->addColumn('action', function($language_strings){
                    $edit_button = '<a href="' . route('admin::languageString.edit', [$language_strings->id]) . '" class="btn btn-icon btn-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    return '<div class="btn-icon-list">' . $edit_button . '</div>';
                })
                 ->addColumn('arname', function ($language_strings) {
                    
                   return $language_strings->translateOrNew('ar')->name;
                
                })
                ->addColumn('for', function($language_strings){
                    if($language_strings->app_or_panel == 1){
                        return 'App';
                    } else{
                        return 'Admin Panel';
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.languageString.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $languages = Language::all();
        return view('admin.languageString.create', ['languages' => $languages]);
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
            $validator_array = [
                'screen_name' => 'required|max:255',
                'name_key'    => 'required|max:255',
            ];
            $duplicate = LanguageString::where('name_key', $request->input('name_key'))
                ->where('app_or_panel', $request->input('app_or_panel'))
                ->first();

            $validator = Validator::make($request->all(), $validator_array);
            if($validator->fails()){
                return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
            }
        } else{
            $validator_array = [
                'screen_name' => 'required|max:255',
                'name_key'    => 'required|max:255',
            ];
            $duplicate = LanguageString::where('name_key', $request->input('name_key'))
                ->where('app_or_panel', $request->input('app_or_panel'))
                ->where('id', '!=', $id)->first();
        }
        if($duplicate){
            return response()->json(['success' => false, 'message' => trans('adminMessages.language_string_duplicate')]);
        } else{
            if($id == NULL){
                $insert_id = LanguageString::create([
                    'app_or_panel' => $request->input('app_or_panel'),
                    'screen_name'  => $request->input('screen_name'),
                    'name_key'     => $request->input('name_key'),
                ]);
                $languages = Language::all();
                foreach($languages as $language){
                    LanguageStringTranslation::create([
                        'name'               => $request->input($language->language_code . '_name'),
                        'language_string_id' => $insert_id->id,
                        'locale'             => $language->language_code,
                    ]);
                }
                return response()->json(['success' => true, 'message' => trans('adminMessages.language_string_inserted')]);
            } else{

                LanguageString::where('id', $id)->update([
                    'screen_name' => $request->input('screen_name'),
                ]);

                $languages = Language::all();
                foreach($languages as $language){
                    LanguageStringTranslation::updateOrCreate([
                        'language_string_id' => $id,
                        'locale'             => $language->language_code,
                    ],
                        [
                            'language_string_id' => $id,
                            'locale'             => $language->language_code,
                            'name'               => $request->input($language->language_code . '_name'),
                        ]);

                }
                return response()->json(['success' => true, 'message' => trans('adminMessages.language_string_updated')]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $language_string = LanguageString::find($id);
        if($language_string){
            $languages = Language::all();
            return view('admin.languageString.edit', ['language_string' => $language_string, 'languages' => $languages]);
        } else{
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Country::where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => trans('adminMessages.country_deleted')]);
    }
}
