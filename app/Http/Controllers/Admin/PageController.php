<?php

namespace App\Http\Controllers\Admin;

use App\Language;
use App\Page;
use App\PageTranslation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $bodies = Page::listsTranslations('name')
                ->select('pages.id', 'pages.for')
                ->get();
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
        $page = Page::find($id);
        if($page){
            $languages = Language::all();
            return view('admin.page.edit', ['page' => $page, 'languages' => $languages]);
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

    }
}
