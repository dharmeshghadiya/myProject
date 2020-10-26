<?php

namespace App\Http\Controllers\Admin;

use App\ReportModel;
use App\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ReportModelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * View Body List
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $reportModel = ReportModel::with('brand','modelYear','toYear','color','companies')->get();
            return Datatables::of($reportModel)
              ->addColumn('company', function($reportModel){

                    return $reportModel->companies->name;
                })
              ->addColumn('brand', function($reportModel){

                    return $reportModel->brand->name;
                })
              ->addColumn('year', function($reportModel){

                    return $reportModel->modelYear->name;
                })
              ->addColumn('toyear', function($reportModel){

                    return $reportModel->toYear->name;
                })
              ->addColumn('color', function($reportModel){

                    return $reportModel->color->name;
                })
                ->addColumn('action', function($reportModel){
                     $delete_button = '<button data-id="' . $reportModel->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '""><i class="bx bx-trash font-size-16 align-middle"></i></button>';

                    return '<div class="btn-icon-list">' . $delete_button . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.reportModel.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * Add Body Page
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * Add Body Details
     */
    public function store(Request $request)
    {

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
     * Edit Body Page
     */
    public function edit($id)
    {
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
     * Body Delete
     */
    public function destroy($id)
    {
          ReportModel::where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => config('languageString.report_model_deleted')]);
    }

}
