<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BecomeADealer;
use App\Helpers\ImageUploadHelper;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the App\Modelslication dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function rydeShare($id, $start_date, $end_date)
    {

        return view('shareRide', [
            'id'         => $id,
            'start_date' => $start_date,
            'end_date'   => $end_date,
        ]);
    }

    public function addBecomeADealer(Request $request)
    {

            $validator_array = [
                'business_name' => 'required',
                'name' => 'required',
                'email' => 'required|unique:users',
                'mobile_number' => 'required',
            ];

            $validator = Validator::make($request->all(), $validator_array);
            if($validator->fails()){
                return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
            }

            $duplicate_email = BecomeADealer::where('email', $request->input('email'))->first();
          if($duplicate_email){
            return response()->json(['success' => false, 'message' => trans('adminMessages.email_duplicate')]);
           }

           $duplicate_mobile_number = BecomeADealer::where(['mobile_number' => $request->input('mobile_number'),'country_code'=>$request->input('country_code')] )->first();

          if($duplicate_mobile_number){
            return response()->json(['success' => false, 'message' => trans('adminMessages.mobile_duplicate')]);
            }
            $trade_license_doc = '';
            $logo = '';
            if($request->hasFile('trade_license_doc')){
                    $files = $request->file('trade_license_doc');
                    $trade_license_doc = ImageUploadHelper::imageUpload($files);
                }

                if($request->hasFile('logo')){
                    $files = $request->file('logo');
                    $logo = ImageUploadHelper::imageUpload($files);
                }

          BecomeADealer::create([
                    'business_name' => $request->input('business_name'),
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'address' => $request->input('address'),
                    'country_code' => $request->input('country_code'),
                    'mobile_number' => $request->input('mobile_number'),
                    'address' => $request->input('address'),
                    'business_number' => $request->input('business_number'),
                    'password' => bcrypt($request->input('password')),
                    'security_deposite' => $request->input('security_deposite'),
                    'license_number' => $request->input('license_number'),
                    'license_expiry_date' => date('Y-m-d',strtotime($request->input('license_expiry_date'))),
                    'bank_name' => $request->input('bank_name'),
                    'bank_address' => $request->input('bank_address'),
                    'bank_contact_number' => $request->input('bank_contact_number'),
                    'beneficiary_name' => $request->input('beneficiary_name'),
                    'bank_code' => $request->input('bank_code'),
                    'bank_iban' => $request->input('bank_iban'),
                    'trade_license_doc'=>$trade_license_doc,
                    'logo'=>$logo,


                ]);


            return response()->json(['success' => true, 'message' => trans('adminMessages.become_a_dealer_created')]);

    }
}
