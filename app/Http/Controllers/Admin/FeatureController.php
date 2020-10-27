<?php

namespace App\Http\Controllers\Admin;


use App\Models\Feature;
use App\Models\FeatureTranslation;
use App\Models\Language;
use App\Models\VehicleFeature;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class FeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            //DB::enableQueryLog();
            $categories = Feature::listsTranslations('name')
                ->select('features.id','features.image')
                ->get();
            // dd(DB::getQueryLog());
            return Datatables::of($categories)
                ->addColumn('action', function ($categories) {
                    $edit_button = '<a href="' . route('admin::feature.edit', [$categories->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $categories->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    return '<div class="btn-icon-list">' . $edit_button . ' ' . $delete_button. '</div>';
                })
                ->addColumn('image', function ($categories) {
                    $url = asset($categories->image);
                    return "<img src='".$url."' style='width:50px' />";
                })
                ->rawColumns(['action','image'])
                ->make(true);
        }
        return view('admin.feature.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $languages = Language::all();
        return view('admin.feature.create', ['languages' => $languages]);
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
                Feature::where('id', $id)->update([
                    'image' => $image_path . '/' . $file_name,
                ]);
            }
        }

        if ($id == NULL) {
            $feature_order = Feature::max('id');
            $insert_id = Feature::create([
                'vehicle_type_id' => 1,
                'image' => $image_path . '/' . $file_name,
                'feature_order' => $feature_order + 1,
            ]);
            $languages = Language::all();
            foreach ($languages as $language) {
                FeatureTranslation::create([
                    'name' => $request->input($language->language_code . '_name'),
                    'feature_id' => $insert_id->id,
                    'locale' => $language->language_code,
                ]);
            }
            return response()->json(['success' => true, 'message' => trans('adminMessages.feature_inserted')]);
        } else {
            $languages = Language::all();
            foreach ($languages as $language) {
                FeatureTranslation::updateOrCreate([
                    'feature_id' => $id,
                    'locale' => $language->language_code,
                ],
                [
                    'feature_id' => $id,
                    'locale' => $language->language_code,
                    'name' => $request->input($language->language_code . '_name')
                ]);

            }
            return response()->json(['success' => true, 'message' => trans('adminMessages.feature_updated')]);
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
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $feature = Feature::find($id);
        if ($feature) {
            $languages = Language::all();
            return view('admin.feature.edit', ['feature' => $feature, 'languages' => $languages]);
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
        Feature::where('id', $id)->delete();
        VehicleFeature::where('feature_id', $id)->delete();


        return response()->json(['success' => true, 'message' => trans('adminMessages.feature_deleted')]);
    }
}
