<?php

namespace App\Http\Controllers\Admin;

use App\Body;
use App\BodyTranslation;
use App\Booking;
use App\CategoryVehicle;
use App\Language;
use App\Ryde;
use App\RydeInstance;
use App\Vehicle;
use App\VehicleExtra;
use App\VehicleFeature;
use App\VehicleNotAvailable;
use App\VehicleOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class BodyController extends Controller
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
            $bodies = Body::listsTranslations('name')
                ->select('bodies.id','bodies.front_image','back_image','right_image','left_image')
                ->get();
            // dd(DB::getQueryLog());
            return Datatables::of($bodies)
                        ->addColumn('front_image', function($bodies){
                    $front_image = '';
                    if(!empty($bodies->front_image)){
                      $front_url = asset($bodies->front_image);
                      $front_image =  "<img src='" . $front_url . "' style='width:100px' />";  
                    }
                    return $front_image; 
                })
            ->addColumn('back_image', function($bodies){
                $back_image = '';
                if(!empty($bodies->back_image)){
                 $back_url = asset($bodies->back_image);
                 $back_image =  "<img src='" . $back_url . "' style='width:100px' />";   
                }
                return $back_image;
                })
            ->addColumn('right_image', function($bodies){
                $right_image = '';
                if(!empty($bodies->right_image)){
                    $right_url = asset($bodies->right_image);
                    $right_image = "<img src='" . $right_url . "' style='width:100px' />";
                }
                return $right_image;   
                })
            ->addColumn('left_image', function($bodies){
                $left_image = '';
                if(!empty($bodies->left_image)){
                 $left_url = asset($bodies->left_image);
                 $left_image = "<img src='" . $left_url . "' style='width:100px' />";
                }
                return $left_image;   
                })
                ->addColumn('action', function($bodies){
                    $edit_button = '<a href="' . route('admin::body.edit', [$bodies->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $bodies->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    return '<div class="btn-icon-list">' . $edit_button . ' ' . $delete_button . '</div>';
                })
                ->rawColumns(['action','front_image','back_image','right_image','left_image'])
                ->make(true);
        }
        return view('admin.body.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $languages = Language::all();
        return view('admin.body.create', ['languages' => $languages]);
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
        $image_path = '';
        $front_file_name = '';
        $back_file_name = '';
        $right_file_name = '';
        $left_file_name = '';
        if($request->hasFile('front_image') && $request->hasFile('back_image') && $request->hasFile('right_image') && $request->hasFile('left_image') ){
            $image_path = 'uploads/' . date('Y') . '/' . date('m');
            $files_front = $request->file('front_image');
            $files_back = $request->file('back_image');
            $files_right = $request->file('right_image');
            $files_left = $request->file('left_image');

            if(!File::exists(public_path() . "/" . $image_path)){
                File::makeDirectory(public_path() . "/" . $image_path, 0777, true);
            }

            $extension_front = $files_front->getClientOriginalExtension();
            $extension_back = $files_back->getClientOriginalExtension();
            $extension_right = $files_right->getClientOriginalExtension();
            $extension_left = $files_left->getClientOriginalExtension();

            $destination_path = public_path() . '/' . $image_path;
            $front_file_name = uniqid() . '.' . $extension_front;
            $back_file_name = uniqid() . '.' . $extension_back;
            $right_file_name = uniqid() . '.' . $extension_right;
            $left_file_name = uniqid() . '.' . $extension_left;

            $files_front->move($destination_path, $front_file_name);

            $files_back->move($destination_path, $back_file_name);

            $files_right->move($destination_path, $right_file_name);

            $files_left->move($destination_path, $left_file_name);

            if($id != NULL){
                Body::where('id', $id)->update([
                    'front_image' => $image_path . '/' . $front_file_name,
                    'back_image' => $image_path . '/' . $back_file_name,
                    'right_image' => $image_path . '/' . $right_file_name,
                    'left_image' => $image_path . '/' . $left_file_name,
                ]);
            }
        }

        if($id == NULL){
            $body_order = Body::max('id');
            $insert_id = Body::create([
                'vehicle_type_id' => 1,
                'body_order'      => $body_order + 1,
                'front_image' => $image_path . '/' . $front_file_name,
                'back_image' => $image_path . '/' . $back_file_name,
                'right_image' => $image_path . '/' . $right_file_name,
                'left_image' => $image_path . '/' . $left_file_name,
            ]);
            $languages = Language::all();
            foreach($languages as $language){
                BodyTranslation::create([
                    'name'    => $request->input($language->language_code . '_name'),
                    'body_id' => $insert_id->id,
                    'locale'  => $language->language_code,
                ]);
            }
            return response()->json(['success' => true, 'message' => trans('adminMessages.body_inserted')]);
        } else{
            $languages = Language::all();
            foreach($languages as $language){
                BodyTranslation::updateOrCreate([
                    'body_id' => $id,
                    'locale'  => $language->language_code,
                ],
                    [
                        'body_id' => $id,
                        'locale'  => $language->language_code,
                        'name'    => $request->input($language->language_code . '_name'),
                    ]);

            }
            return response()->json(['success' => true, 'message' => trans('adminMessages.body_updated')]);
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
        $body = Body::find($id);
        if($body){
            $languages = Language::all();
            return view('admin.body.edit', ['body' => $body, 'languages' => $languages]);
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
        $rydeInstances = RydeInstance::where('body_id', $id)->get();
        foreach($rydeInstances as $rydeInstance){
            $rydes = Ryde::where('id', $rydeInstance->ryde_id)->get();

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
            Ryde::where('id', $rydeInstance->ryde_id)->delete();
        }
        RydeInstance::where('body_id', $id)->delete();
        Body::where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => trans('adminMessages.body_deleted')]);
    }
}
