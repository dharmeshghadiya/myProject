<?php

namespace App\Http\Controllers\Admin;

use App\Booking;
use App\Brand;
use App\CategoryVehicle;
use App\Ryde;
use App\Helpers\ImageUploadHelper;
use App\BrandTranslation;
use App\Language;
use App\Vehicle;
use App\VehicleExtra;
use App\VehicleFeature;
use App\VehicleNotAvailable;
use App\VehicleOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $brands = Brand::get();
            return Datatables::of($brands)
                ->addColumn('action', function($brands){
                    $edit_button = '<a href="' . route('admin::brand.edit', [$brands->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $brands->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    return '<div class="btn-icon-list">' . $edit_button . ' ' . $delete_button . '</div>';
                })
                ->addColumn('brand_logo', function($brands){
                    return "<img src='" . asset($brands->image) . "' style='width:100px' />";
                })
                ->rawColumns(['action', 'brand_logo'])
                ->make(true);
        }
        return view('admin.brand.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $languages = Language::all();
        return view('admin.brand.create', ['languages' => $languages]);
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
            $brand_logo = NULL;
            if($request->hasFile('brand_logo')){
                $files = $request->file('brand_logo');
                $brand_logo = ImageUploadHelper::imageUpload($files);
            }

            $brand_order = Brand::max('id');
            $insert_id = Brand::create([
                'vehicle_type_id' => 1,
                'brand_order'     => $brand_order + 1,
                'brand_logo'      => $brand_logo,
            ]);
            $languages = Language::all();
            foreach($languages as $language){
                BrandTranslation::create([
                    'name'     => $request->input($language->language_code . '_name'),
                    'brand_id' => $insert_id->id,
                    'locale'   => $language->language_code,
                ]);
            }
            return response()->json(['success' => true, 'message' => trans('adminMessages.brand_inserted')]);
        } else{

            if($request->hasFile('brand_logo')){
                $files = $request->file('brand_logo');
                $brand_logo = ImageUploadHelper::imageUpload($files);
                Brand::where('id', $id)->update([
                    'brand_logo' => $brand_logo,
                ]);
            }

            $languages = Language::all();
            foreach($languages as $language){
                BrandTranslation::updateOrCreate([
                    'brand_id' => $id,
                    'locale'   => $language->language_code,
                ],
                    [
                        'brand_id' => $id,
                        'locale'   => $language->language_code,
                        'name'     => $request->input($language->language_code . '_name'),
                    ]);

            }
            return response()->json(['success' => true, 'message' => trans('adminMessages.brand_updated')]);
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
        $brand = Brand::find($id);
        if($brand){
            $languages = Language::all();
            return view('admin.brand.edit', ['brand' => $brand, 'languages' => $languages]);
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

        $rydes = Ryde::where('brand_id', $id)->get();
        foreach($rydes as $ryde){
            $vehicles = Vehicle::where('ryde_id', $ryde->id)->get();
            foreach($vehicles as $vehicle){
                VehicleExtra::where('vehicle_id', $vehicle->id)->delete();
                VehicleFeature::where('vehicle_id', $vehicle->id)->delete();
                VehicleOption::where('vehicles_id', $vehicle->id)->delete();
                CategoryVehicle::where('vehicle_id', $vehicle->id)->delete();
                VehicleNotAvailable::where('vehicle_id', $vehicle->id)->delete();
                Booking::where('vehicle_id', $vehicle->id)->delete();
            }
            Vehicle::where('ryde_id', $ryde->id)->delete();
        }
        Ryde::where('brand_id', $id)->delete();
        Brand::where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => trans('adminMessages.brand_deleted')]);
    }
}
