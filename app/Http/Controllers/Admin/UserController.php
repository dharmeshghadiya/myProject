<?php

namespace App\Http\Controllers\Admin;


use App\Booking;
use App\BranchExtra;
use App\CategoryVehicle;
use App\Commission;
use App\Company;
use App\CompanyAddress;
use App\CompanyTime;
use App\DealerExtra;
use App\Device;
use App\Language;
use App\ReportProblem;
use App\User;
use App\Country;
use App\Vehicle;
use App\VehicleExtra;
use App\VehicleFeature;
use App\VehicleNotAvailable;
use App\VehicleOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){

            $users = User::where('user_type', 'user')->get();

            // dd(DB::getQueryLog());
            return Datatables::of($users)
                ->addColumn('status', function($users){
                    if($users->status == 'Active'){
                        $status = '<span class=" badge badge-success">' . config('languageString.active') . '</span>';
                    } else{
                        $status = '<span  class=" badge badge-danger">' . config('languageString.deactive') . '</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function($users){

                    $edit_button = '<a href="' . route('admin::users.edit', [$users->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $users->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '""><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    //$details_btn = '<a href="' . route('admin::dealer.show', [$users->id]) . '" class="dealer-details btn btn-primary btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.view_details') . '""><i class="bx bx-bullseye font-size-16 align-middle"></i></a>';

                    $status = 'Active';
                    $translate_status = config('languageString.active');
                    if($users->status == 'Active'){
                        $status = 'DeActive';
                        $translate_status = config('languageString.deactive');
                    }
                    $status_button = '<button data-id="' . $users->id . '" data-status="' . $status . '" class="status-change btn btn-warning btn-icon" data-effect="effect-fall" data-toggle="tooltip" data-placement="top" title="' . $translate_status . '" ><i class="bx bx-refresh font-size-16 align-middle"></i></button>';

                    return '<div class="btn-icon-list">' . $edit_button . ' ' . $delete_button . ' ' . $status_button . '</div>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $languages = Language::all();
        return view('admin.color.create', ['languages' => $languages]);
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

            $insert_id = user::create([
                'name'         => $request->input('name'),
                'mobile_no'    => $request->input('phone_no'),
                'country_code' => $request->input('country_code'),
            ]);

            return response()->json(['success' => true, 'message' => trans('adminMessages.user_inserted')]);
        } else{
            $user = User::where('id', $id)->first();
            $user->name = $request->input('name');
            $user->mobile_no = $request->input('phone_no');
            $user->country_code = $request->input('country_code');
            $user->save();

            return response()->json(['success' => true, 'message' => trans('adminMessages.user_updated')]);
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
        $user = User::find($id);
        if($user){
            $countries = Country::all();
            return view('admin.user.edit', ['user' => $user, 'countries' => $countries]);
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
        $companyAddresses = CompanyAddress::where('user_id', $id)->get();
        foreach($companyAddresses as $companyAddress){
            Booking::where('company_address_id', $companyAddress->id)->delete();
            CompanyTime::where('company_address_id', $companyAddress->id)->delete();
            Commission::where('company_address_id', $companyAddress->id)->delete();
            BranchExtra::where('company_address_id', $companyAddress->id)->delete();
            $vehicles = Vehicle::where('company_address_id', $companyAddress->id)->get();
            foreach($vehicles as $vehicle){
                VehicleExtra::where('vehicle_id', $vehicle->id)->delete();
                VehicleFeature::where('vehicle_id', $vehicle->id)->delete();
                VehicleOption::where('vehicles_id', $vehicle->id)->delete();
                CategoryVehicle::where('vehicle_id', $vehicle->id)->delete();
                VehicleNotAvailable::where('vehicle_id', $vehicle->id)->delete();
                Booking::where('vehicle_id', $vehicle->id)->delete();
            }
            Vehicle::where('company_address_id', $companyAddress->id)->delete();
        }
        DealerExtra::where('user_id', $id)->delete();
        Device::where('user_id', $id)->delete();
        ReportProblem::where('user_id', $id)->delete();
        user::where('id', $id)->delete();
        Company::where('user_id', $id)->delete();
        return response()->json(['success' => true, 'message' => trans('adminMessages.user_deleted')]);
    }

    public function userChangeStatus($id, $status)
    {
        User::where('id', $id)->update(['status' => $status]);
        return response()->json(['success' => true, 'message' => trans('adminMessages.user_status')]);
    }
}
