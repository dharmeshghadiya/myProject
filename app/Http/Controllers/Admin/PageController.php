<?php

namespace App\Http\Controllers\Admin;

use App\Models\Language;
use App\Models\Page;
use App\Models\PageTranslation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PageController extends Controller
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
            $bodies = Page::listsTranslations('name')
                ->select('pages.id', 'pages.for')
                ->get();
            // dd(DB::getQueryLog());
            return Datatables::of($bodies)
                ->addColumn('action', function($bodies){
                    return '<a href="' . route('admin::page.edit', [$bodies->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                })
                ->addColumn('for', function($bodies){
                    return config('languageString.'.$bodies->for);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.page.index');
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


        $languages = Language::all();
        foreach($languages as $language){
            PageTranslation::updateOrCreate([
                'page_id' => $id,
                'locale'  => $language->language_code,
            ],
                [
                    'page_id'     => $id,
                    'locale'      => $language->language_code,
                    'name'        => $request->input($language->language_code . '_name'),
                    'description' => $request->input($language->language_code . '_description'),
                ]);

        }
        return response()->json(['success' => true, 'message' => trans('adminMessages.page_updated')]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $page = Page::find($id);
        if($page){
            $languages = Language::all();
            return view('admin.page.edit', ['page' => $page, 'languages' => $languages]);
        } else{
            abort(404);
        }
    }

}
