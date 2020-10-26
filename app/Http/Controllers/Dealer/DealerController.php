<?php

namespace App\Http\Controllers\Dealer;

use App\Booking;
use App\Commission;
use App\Company;
use App\CompanyAddress;
use App\CompanyTime;
use App\Country;
use App\CountryTranslation;
use App\Helpers\ImageUploadHelper;
use App\Language;
use App\Setting;
use App\User;
use App\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DealerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
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

        if($id == NULL){
            $validator_array = [
                'name'                => 'required|max:255',
                'email'               => 'required|max:255|email',
                'password'            => 'required|min:8|max:20',
                'country_code'        => 'required',
                'mobile_no'           => 'required|max:30',
                'iban'                => 'required|max:255',
                'license_number'      => 'required|max:255',
                'image'               => 'required|image|mimes:jpeg,png,jpg',
                'business_name'       => 'required|max:255',
                'business_number'     => 'required|max:255',
                'license_expiry_date' => 'required',
            ];
            $duplicate_email = User::where('email', $request->input('email'))->first();
            $duplicate_mobile_no = User::where('mobile_no', $request->input('mobile_no'))->first();

        } else{
            $validator_array = [
                'name'                => 'required|max:255',
                'country_code'        => 'required',
                'mobile_no'           => 'required|max:30',
                'iban'                => 'required|max:255',
                'license_number'      => 'required|max:255',
            ];
            //$duplicate_email = User::where('email', $request->input('email'))->where('id', '!=', $id)->first();
            $duplicate_mobile_no = User::where('mobile_no', $request->input('mobile_no'))->where('id', '!=', $id)->first();
        }

        $validator = Validator::make($request->all(), $validator_array);
        if($validator->fails()){
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        if(isset($duplicate_email) && !empty($duplicate_email)){
            return response()->json(['success' => false, 'message' => trans('adminMessages.email_duplicate')]);
        } else if($duplicate_mobile_no){
            return response()->json(['success' => false, 'message' => trans('adminMessages.mobile_duplicate')]);
        } else{


            if($id == NULL){

                $insert_id = User::create([
                    'name'         => $request->input('name'),
                    'email'        => $request->input('email'),
                    'country_code' => $request->input('country_code'),
                    'mobile_no'    => $request->input('mobile_no'),
                    'panel_mode'   => 1,
                    'locale'       => 'en',
                    'user_type'    => 'company',
                    'password'     => bcrypt($request->input('password')),

                ]);


                $company = new Company();
                if($request->hasFile('image')){
                    $files = $request->file('image');
                    $company->trade_license_image = ImageUploadHelper::imageUpload($files);
                }

                if($request->hasFile('dealer_logo')){
                    $files = $request->file('dealer_logo');
                    $company->dealer_logo = ImageUploadHelper::imageUpload($files);
                }


                $company->user_id = $insert_id->id;
                $company->name = $request->input('business_name');
                $company->contact_name = $request->input('name');
                $company->email = $request->input('email');
                $company->country_code = $request->input('country_code');
                $company->mobile_no = $request->input('mobile_no');
                $company->iban = $request->input('iban');
                $company->license_number = $request->input('license_number');
                $company->business_number = $request->input('business_number');
                $company->security_deposit = $request->input('security_deposit');
                $company->license_expiry_date = date('Y-m-d', strtotime($request->input('license_expiry_date')));
                $company->bank_name = $request->input('bank_name');
                $company->bank_address = $request->input('bank_address');
                $company->bank_contact_number = $request->input('bank_contact_number');
                $company->beneficiary_name = $request->input('beneficiary_name');
                $company->bank_code = $request->input('bank_code');
                $company->commission_percentage = 20;
                $company->save();

                return response()->json(['success' => true, 'message' => trans('adminMessages.dealer_inserted')]);
            } else{

                User::where('id', $id)->update([
                    'name'      => $request->input('name'),
                    'country_code' => $request->input('country_code'),
                    'mobile_no' => $request->input('mobile_no'),
                ]);


                $get_images = Company::where('user_id', $id)->first();
                $dealer_logo = $get_images->dealer_logo;
                $trade_license_image = $get_images->trade_license_image;
                if($request->hasFile('image')){
                    $files = $request->file('image');
                    $trade_license_image = ImageUploadHelper::imageUpload($files);
                }

                if($request->hasFile('dealer_logo')){
                    $files = $request->file('dealer_logo');
                    $dealer_logo = ImageUploadHelper::imageUpload($files);
                }

                Company::where('user_id', $id)->update([
                    'name'                => $request->input('business_name'),
                    'contact_name'        => $request->input('name'),
                    
                    'mobile_no'           => $request->input('mobile_no'),
                    'business_number'     => $request->input('business_number'),
                    'security_deposit'    => $request->input('security_deposit'),
                    'country_code' =>        $request->input('country_code'),
                    'bank_name'           => $request->input('bank_name'),
                    'bank_address'        => $request->input('bank_address'),
                    'bank_contact_number' => $request->input('bank_contact_number'),
                    'beneficiary_name'    => $request->input('beneficiary_name'),
                    'bank_code'           => $request->input('bank_code'),
                    'dealer_logo'         => $dealer_logo,
                    'trade_license_image' => $trade_license_image,
                ]);

                return response()->json(['success' => true, 'message' => trans('adminMessages.dealer_updated')]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::with('companies')->find($id);

        $countries = Country::all();

        if($user){
            $languages = Language::all();
            return view('dealer.dealer.edit', ['user' => $user, 'languages' => $languages,'countries'=>$countries]);
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
