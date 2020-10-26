<?php

namespace App\Http\Controllers\Admin;

use App\EmailString;
use App\EmailStringTranslation;
use App\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class EmailStringController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            //DB::enableQueryLog();
            $email_strings = EmailString::listsTranslations('name')
                ->select('email_strings.id', 'email_strings.template_name', 'email_strings.name_key')
                ->get();
            // dd(DB::getQueryLog());
            return Datatables::of($email_strings)
                ->addColumn('action', function($email_strings){
                    $edit_button = '<a href="' . route('admin::emailString.edit', [$email_strings->id]) . '" class="btn btn-icon btn-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="' . config('emailString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    return '<div class="btn-icon-list">' . $edit_button . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.emailString.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $languages = Language::all();
        return view('admin.emailString.create', ['languages' => $languages]);
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
                'template_name' => 'required|max:255',
                'name_key'    => 'required|max:255',
            ];
            $duplicate = EmailString::where('name_key', $request->input('name_key'))
                ->where('template_name', $request->input('template_name'))
                ->first();

            $validator = Validator::make($request->all(), $validator_array);
            if($validator->fails()){
                return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
            }
        } else{
            $validator_array = [
                'template_name' => 'required|max:255',
                'name_key'    => 'required|max:255',
            ];
            $duplicate = EmailString::where('name_key', $request->input('name_key'))
                ->where('template_name', $request->input('template_name'))
                ->where('id', '!=', $id)->first();
        }
        if($duplicate){
            return response()->json(['success' => false, 'message' => trans('adminMessages.email_string_duplicate')]);
        } else{
            if($id == NULL){
                $insert_id = EmailString::create([
                    
                    'template_name'  => $request->input('template_name'),
                    'name_key'     => $request->input('name_key'),
                ]);
                $languages = Language::all();
                foreach($languages as $language){
                    EmailStringTranslation::create([
                        'name'               => $request->input($language->language_code . '_name'),
                        'email_string_id' => $insert_id->id,
                        'locale'             => $language->language_code,
                    ]);
                }
                return response()->json(['success' => true, 'message' => trans('adminMessages.email_string_inserted')]);
            } else{

                EmailString::where('id', $id)->update([
                    'template_name' => $request->input('template_name'),
                ]);

                $languages = Language::all();
                foreach($languages as $language){
                    EmailStringTranslation::updateOrCreate([
                        'email_string_id' => $id,
                        'locale'             => $language->language_code,
                    ],
                        [
                            'email_string_id' => $id,
                            'locale'             => $language->language_code,
                            'name'               => $request->input($language->language_code . '_name'),
                        ]);

                }
                return response()->json(['success' => true, 'message' => trans('adminMessages.email_string_updated')]);
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
        $email_string = EmailString::find($id);
        if($email_string){
            $languages = Language::all();
            return view('admin.emailString.edit', ['email_string' => $email_string, 'languages' => $languages]);
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
