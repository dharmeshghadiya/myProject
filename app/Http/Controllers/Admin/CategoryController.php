<?php

namespace App\Http\Controllers\Admin;


use App\Category;
use App\CategoryVehicle;
use App\CategoryTranslation;
use App\Language;
use App\Company;
use App\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Config;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            //DB::enableQueryLog();
            $categories = Category::listsTranslations('name')
                ->select('categories.id','categories.image')
                ->get();
            // dd(DB::getQueryLog());
            return Datatables::of($categories)
                ->addColumn('action', function ($categories) {
                    $edit_button = '<a href="' . route('admin::category.edit', [$categories->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $categories->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';

                    $view_detail_button = '<a href="' . route('admin::categoryRydeDetails', [$categories->id]) . '" class="vehicle-details btn btn-secondary btn-icon" data-effect="effect-fall" data-toggle="tooltip" data-placement="top" title="Ryde Details"><i class="bx bx-bullseye font-size-16 align-middle"></i></a>';
                    return '<div class="btn-icon-list">' .$edit_button . ' ' . $delete_button.' '. $view_detail_button. '</div>';
                })
                ->addColumn('image', function ($categories) {
                    $url = asset($categories->image);
                    return "<img src='".$url."' style='width:100px' />";
                })
                ->rawColumns(['action','image'])
                ->make(true);
        }
        return view('admin.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $languages = Language::all();
        return view('admin.category.create', ['languages' => $languages]);
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

        if ($id == NULL) {
            $validator_array = [
                'image' => 'required|image|mimes:jpeg,png,jpg',
            ];
            $validator = Validator::make($request->all(), $validator_array);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
            }
        }

        $image_path = $file_name = '';
        if ($request->hasFile('image')) {
            $image_path = 'uploads/' . date('Y') . '/' . date('m');
            $files = $request->file('image');

            if (!File::exists(public_path() . "/" . $image_path)) {
                File::makeDirectory(public_path() . "/" . $image_path, 0777, true);
            }

            $extension = $files->getClientOriginalExtension();
            $destination_path = public_path() . '/' . $image_path;
            $file_name = uniqid() . '.' . $extension;
            $files->move($destination_path, $file_name);

            if ($id != NULL) {
                Category::where('id', $id)->update([
                    'image' => $image_path . '/' . $file_name,
                ]);
            }
        }

        if ($id == NULL) {
            $category_order = Category::max('id');
            $insert_id = Category::create([
                'image' => $image_path . '/' . $file_name,
                'category_order' => $category_order + 1,
            ]);
            $languages = Language::all();
            foreach ($languages as $language) {
                CategoryTranslation::create([
                    'name' => $request->input($language->language_code . '_name'),
                    'category_id' => $insert_id->id,
                    'locale' => $language->language_code,
                ]);
            }
            return response()->json(['success' => true, 'message' => trans('adminMessages.category_inserted')]);
        } else {
            $languages = Language::all();
            foreach ($languages as $language) {
                CategoryTranslation::updateOrCreate([
                    'category_id' => $id,
                    'locale' => $language->language_code,
                ],
                    [
                        'category_id' => $id,
                        'locale' => $language->language_code,
                        'name' => $request->input($language->language_code . '_name')
                    ]);

            }
            return response()->json(['success' => true, 'message' => trans('adminMessages.category_updated')]);
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
        $category = Category::find($id);
        if ($category) {
            $languages = Language::all();
            return view('admin.category.edit', ['category' => $category, 'languages' => $languages]);
        } else {
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
        Category::where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => trans('adminMessages.category_deleted')]);
    }

    public function categoryRydeDetails($id)
    {
        $category = Category::where('categories.id',$id)->listsTranslations('name')
                ->select('categories.id','categories.image')
                ->first();
        $category_vehicle = CategoryVehicle::where('category_id', $id)->with(['companies','companyAddress','vehicle' => function($query){
                        $query->with(['ryde' => function($query){
                        $query->with('brand', 'modelYear', 'color','rydeInstance');
                    },
                    ]);
                    },
    ])->get();

        return view('admin.category.categoryRydeDetails', ['category_vehicles' => $category_vehicle,'category'=>$category]);
    }

    public function addCategoryVehicle($id){

        $category = Category::where('categories.id',$id)->listsTranslations('name')
                ->select('categories.id','categories.image')
                ->first();
        $companies = Company::all();
        if ($category) {
            $languages = Language::all();
            return view('admin.category.addCategoryVehicle', ['category' => $category, 'companies'=>$companies]);
        } else {
            abort(404);
        }
    }

    public function getVehicle(Request $request)
    {
        $company_id = $request->company_id;
        $company_address_id = $request->company_address_id;
        $category_id = $request->category_id;

        $vehicle_ids = CategoryVehicle::where(['category_id'=> $category_id,'company_id'=>$company_id,'company_address_id'=>$company_address_id])->pluck('vehicle_id')->toArray();


        if(!empty($company_id) && !empty($company_address_id) ){
            $vehicles = Vehicle::where(['company_id'=> $company_id,'company_address_id'=>$company_address_id])
            ->with([
                'ryde' => function($query){
                    $query->with('brand', 'modelYear', 'color', 'rydeInstance');
                },
            ])->get();

            if($vehicles){
                return view('admin.category.vehicleShow', [
                    'vehicles' => $vehicles,
                    'vehicle_ids' => $vehicle_ids
                ]);

            } else{
                return view('admin.category.vehicleShow');
            }
        }
    }



    public function categoryVehicleAdd(Request $request)
    {
            $validator_array = [
                'company_id' => 'required',
                'company_address_id' => 'required',
                'vehicle_id'    => 'required',
            ];

        $validator = Validator::make($request->all(), $validator_array);
        if($validator->fails()){
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }


        $vehicles = $request->input('vehicle_id');
        foreach($vehicles as $key=>$vehicle_id ){
        $company_address_id = CategoryVehicle::create([
                'category_id'        => $request->input('category_id'),
                'company_id'          => $request->input('company_id'),
                'company_address_id'  => $request->input('company_address_id'),
                'vehicle_id'           =>$vehicle_id
            ]);
        }

        return response()->json(['success' => true, 'message' => trans('adminMessages.category_ryde_inserted')]);
    }


    public function deleteCategoryVehicle($id)
    {
        CategoryVehicle::where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => trans('adminMessages.ryde_category_deleted')]);
    }

}
