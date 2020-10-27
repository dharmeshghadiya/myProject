<?php

namespace App\Http\Controllers\Admin;

use App\Models\ReportModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ReportModelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * View Body List
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            //DB::enableQueryLog();
            $reportModel = ReportModel::with('brand','modelYear','toYear','color','companies')->get();
            // dd(DB::getQueryLog());
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
