<?php

namespace App\Http\Controllers\Admin;


use App\Models\BranchExtra;
use App\Models\CompanyAddress;
use App\Models\DealerExtra;
use App\Models\GlobalExtra;
use App\Models\GlobalExtraTranslation;
use App\Models\Language;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleExtra;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class GlobalExtraController extends Controller
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
            $extras = GlobalExtra::listsTranslations('name')
                ->select('global_extras.id')
                ->get();
            // dd(DB::getQueryLog());
            return Datatables::of($extras)
                ->addColumn('action', function($extras){
                    $edit_button = '<a href="' . route('admin::globalExtra.edit', [$extras->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $extras->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    return '<div class="btn-icon-list">' . $edit_button . ' ' . $delete_button . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.globalExtra.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $languages = Language::all();
        return view('admin.globalExtra.create', ['languages' => $languages]);
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
            $extra_order = GlobalExtra::max('id');
            $insert_id = GlobalExtra::create([
                'extra_order' => $extra_order + 1,
            ]);
            $languages = Language::all();
            foreach($languages as $language){
                GlobalExtraTranslation::create([
                    'name'            => $request->input($language->language_code . '_name'),
                    'description'     => $request->input($language->language_code . '_description'),
                    'global_extra_id' => $insert_id->id,
                    'locale'          => $language->language_code,
                ]);
            }
            $users = User::where('user_type', 'company')->get();
            foreach($users as $user){
                DealerExtra::create([
                    'user_id'         => $user->id,
                    'global_extra_id' => $insert_id->id,
                    'type'            => 2,
                ]);

                $branches = CompanyAddress::where('user_id', $user->id)->get();
                foreach($branches as $branch){
                    BranchExtra::create([
                        'company_id'         => $branch->company_id,
                        'company_address_id' => $branch->id,
                        'global_extra_id'    => $insert_id->id,
                        'type'               => 2,
                    ]);
                }

                $vehicles = Vehicle::all();
                foreach($vehicles as $vehicle){
                    VehicleExtra::create([
                        'vehicle_id'      => $vehicle->id,
                        'global_extra_id' => $insert_id->id,
                        'type'            => 2,
                        'price'           => 0,
                    ]);
                }
            }


            return response()->json(['success' => true, 'message' => trans('adminMessages.extra_inserted')]);
        } else{
            $languages = Language::all();
            foreach($languages as $language){
                GlobalExtraTranslation::updateOrCreate([
                    'global_extra_id' => $id,
                    'locale'          => $language->language_code,
                ],
                    [
                        'global_extra_id' => $id,
                        'locale'          => $language->language_code,
                        'description'     => $request->input($language->language_code . '_description'),
                        'name'            => $request->input($language->language_code . '_name'),
                    ]);

            }
            return response()->json(['success' => true, 'message' => trans('adminMessages.extra_updated')]);
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
        $extra = GlobalExtra::find($id);
        if($extra){
            $languages = Language::all();
            return view('admin.globalExtra.edit', ['extra' => $extra, 'languages' => $languages]);
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
        GlobalExtra::where('id', $id)->delete();
        VehicleExtra::where('global_extra_id', $id)->delete();

        return response()->json(['success' => true, 'message' => config('languageString.extra_deleted')]);
    }
}
