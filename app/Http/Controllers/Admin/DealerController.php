<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\BranchExtra;
use App\Models\Commission;
use App\Models\Company;
use App\Models\CompanyAddress;
use App\Models\CompanyTime;
use App\Models\Country;
use App\Models\CountryTranslation;
use App\Models\DealerExtra;
use App\Models\GlobalExtra;
use App\Helpers\ImageUploadHelper;
use App\Models\Language;
use App\Mail\DealerWelcomeEmail;
use App\Models\Setting;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DealerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * View Dealers List
     */
    public function index(Request $request)
    {

        if($request->ajax()){
            $country_id = $request->input('country_id');
            //DB::enableQueryLog();
            if($country_id != NULL){
                $user_ids = CompanyAddress::where('country_id', $country_id)->pluck('user_id')->toArray();
                $dealers = User::wherein('id', $user_ids)->where('user_type', 'company')->where('parent_id', 0)->with('companies')->get();
            } else{
                $dealers = User::where('user_type', 'company')->where('parent_id', 0)->with('companies')->get();
                //dd($dealers);
            }

            // dd(DB::getQueryLog());
            return Datatables::of($dealers)
                ->addColumn('status', function($dealers){
                    if($dealers->status == 'Active'){
                        $status = '<span class=" badge badge-success">' . config('languageString.active') . '</span>';
                    } else{
                        $status = '<span  class=" badge badge-danger">' . config('languageString.deactive') . '</span>';
                    }
                    return $status;
                })
                ->addColumn('rydeCount', function($dealers){
                    return Vehicle::where('company_id', $dealers->companies->id)
                        ->count();
                })
                ->addColumn('action', function($dealers){

                    $edit_button = '<a href="' . route('admin::dealer.edit', [$dealers->id]) . '"
                    class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top"
                    title="' . config('languageString.edit') . '">
                    <i class="bx bx-pencil font-size-16 align-middle"></i></a>';

                    $delete_button = '<button data-id="' . $dealers->id . '"
                     class="delete-single btn btn-danger btn-icon"
                      data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"">
                      <i class="bx bx-trash font-size-16 align-middle"></i></button>';

                    $details_btn = '<a href="' . route('admin::dealer.show', [$dealers->id]) . '"
                     class="dealer-details btn btn-secondary btn-icon" data-toggle="tooltip" data-placement="top"
                     title="' . config('languageString.view_details') . '"">
                     <i class="bx bx-bullseye font-size-16 align-middle"></i></a>';

                    $status = 'Active';
                    $translate_status = config('languageString.active');
                    if($dealers->status == 'Active'){
                        $status = 'DeActive';
                        $translate_status = config('languageString.deactive');
                    }
                    $status_button = '<button data-id="' . $dealers->id . '" data-status="' . $status . '"
                     class="status-change btn btn-warning btn-icon" data-effect="effect-fall"
                      data-toggle="tooltip" data-placement="top" title="' . $translate_status . '">
                      <i class="bx bx-refresh font-size-16 align-middle"></i>
                      </button>';

                    return '<div class="btn-icon-list">' . $edit_button . ' ' . $delete_button . ' ' . $details_btn . ' ' . $status_button . '</div>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        $countries = Country::all();
        return view('admin.dealer.index', ['countries' => $countries]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * Add Dealer Page
     */
    public function create()
    {
        //Add Dealer Page
        $languages = Language::all();
        $setting = Setting::where('meta_key', 'default_radius_km')->select('meta_value')->first();
        $weeks = [
            'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday',
        ];
        $global_extras = GlobalExtra::all();
        $countries = Country::all();
        return view('admin.dealer.create', [
            'languages'        => $languages,
            'service_distance' => $setting->meta_value,
            'weeks'            => $weeks,
            'countries'        => $countries,
            'global_extras'    => $global_extras,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * Add Dealer Details
     */
    public function store(Request $request)
    {
        //Store Dealer Details

        $id = $request->input('edit_value');

        if($id == NULL){
            $validator_array = [
                'name'                => 'required|max:255',
                'email'               => 'required|max:255|email',
                'password'            => 'required|min:8|max:20',
                'country_code'        => 'required|max:5',
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
                'email'               => 'required|max:255|email',
                'country_code'        => 'required|max:5',
                'mobile_no'           => 'required|max:30',
                'iban'                => 'required|max:255',
                'license_number'      => 'required|max:255',
                'license_expiry_date' => 'required',

            ];
            $duplicate_email = User::where('email', $request->input('email'))->where('id', '!=', $id)->first();
            $duplicate_mobile_no = User::where('mobile_no', $request->input('mobile_no'))->where('id', '!=', $id)->first();
        }

        $validator = Validator::make($request->all(), $validator_array);
        if($validator->fails()){
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        if($duplicate_email){
            return response()->json(['success' => false, 'message' => trans('adminMessages.email_duplicate')]);
        } else if($duplicate_mobile_no){
            return response()->json(['success' => false, 'message' => trans('adminMessages.mobile_duplicate')]);
        } else{

            $country = Country::where('code', str_replace('+', '', $request->input('country_code')))->first();
            $country_id = NULL;
            if($country){
                $country_id = $country->id;
            }

            if($id == NULL){
                $insert_id = User::create([
                    'name'         => $request->input('name'),
                    'email'        => $request->input('email'),
                    'country_code' => $request->input('country_code'),
                    'mobile_no'    => $request->input('mobile_no'),
                    'panel_mode'   => 1,
                    'locale'       => 'en',
                    'user_type'    => 'company',
                    'status'       => $request->input('status'),
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
                $company->country_id = $country_id;
                $company->commission_percentage = 20;
                $company->save();

                $extras = $request->input('extra');
                $extra_ids = $request->input('extra_ids');

                foreach($extras as $key => $extra){
                    $dealer_extra = new DealerExtra();
                    $dealer_extra->user_id = $insert_id->id;
                    $dealer_extra->global_extra_id = $extra_ids[$key];
                    $dealer_extra->type = $extra;
                    $dealer_extra->save();
                }

                $array = [
                    'name'            => $request->input('name'),
                    'subject'         => Config('email_string.dealer_welcome_mail_subject'),
                    'main_title_text' => Config('email_string.dealer_welcome_mail_title'),
                ];
                Mail::to($request->input('email'))->send(new DealerWelcomeEmail($array));
                return response()->json(['success' => true, 'message' => trans('adminMessages.dealer_inserted')]);
            } else{

                User::where('id', $id)->update([
                    'name'      => $request->input('name'),
                    'email'     => $request->input('email'),
                    'mobile_no' => $request->input('mobile_no'),
                    'status'    => $request->input('status'),
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
                    'email'               => $request->input('email'),
                    'mobile_no'           => $request->input('mobile_no'),
                    'business_number'     => $request->input('business_number'),
                    'security_deposit'    => $request->input('security_deposit'),
                    'license_expiry_date' => date('Y-m-d', strtotime($request->input('license_expiry_date'))),
                    'bank_name'           => $request->input('bank_name'),
                    'bank_address'        => $request->input('bank_address'),
                    'bank_contact_number' => $request->input('bank_contact_number'),
                    'beneficiary_name'    => $request->input('beneficiary_name'),
                    'bank_code'           => $request->input('bank_code'),
                    'dealer_logo'         => $dealer_logo,
                    'country_id'          => $country_id,
                    'trade_license_image' => $trade_license_image,
                ]);

                $extras = $request->input('extra');
                $extra_ids = $request->input('extra_ids');

                if($extras != NULL){
                    foreach($extras as $key => $extra){
                        $dealer_extra_check = DealerExtra::where('user_id', $id)->where('global_extra_id', $extra_ids[$key])->first();
                        if($dealer_extra_check){
                            $dealer_extra = DealerExtra::find($dealer_extra_check->id);
                            $dealer_extra->user_id = $id;
                            $dealer_extra->global_extra_id = $extra_ids[$key];
                            $dealer_extra->type = $extra;
                            $dealer_extra->save();
                        }
                    }
                }

                return response()->json(['success' => true, 'message' => trans('adminMessages.dealer_updated')]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * Show Dealer Details
     */
    public function show($id)
    {
        //Show Dealer Details
        $details = User::with('companies')->find($id);
        if($details){
            $branches = CompanyAddress::where('company_id', $details->companies->id)->get();
            $total_current_year_booking = Booking::where('company_id', $details->companies->id)
                ->whereYear('created_at', date('Y'))
                ->count();
            $total_current_month_booking = Booking::where('company_id', $details->companies->id)
                ->whereYear('created_at', date('Y'))
                ->whereMonth('created_at', date('m'))
                ->count();
            $total_current_year_amount = Commission::where('company_id', $details->companies->id)
                ->whereYear('created_at', date('Y'))
                ->where('type', 'Credit')
                ->sum('commission_amount');
            $total_current_month_amount = Commission::where('company_id', $details->companies->id)
                ->whereYear('created_at', date('Y'))
                ->whereMonth('created_at', date('m'))
                ->where('type', 'Credit')
                ->sum('commission_amount');
            $credit_balance = Commission::where('company_id', $details->companies->id)
                ->where('type', 'Credit')
                ->sum('commission_amount');

            $debit_balance = Commission::where('company_id', $details->companies->id)
                ->where('type', 'Debit')
                ->sum('commission_amount');

            $due_balance = $credit_balance - $debit_balance;
            $total_today_ride_booked = Booking::where('company_id', $details->companies->id)
                ->where('created_at', Carbon::today())
                ->count();

            $total_ride = Vehicle::where('company_id', $details->companies->id)
                ->count();

            $maintenance_ride = Vehicle::where('company_id', $details->companies->id)
                ->where('status', 'InActive')
                ->count();

            return view('admin.dealer.show', [
                'details'                     => $details,
                'branches'                    => $branches,
                'total_current_year_booking'  => $total_current_year_booking,
                'total_current_month_booking' => $total_current_month_booking,
                'total_current_year_amount'   => $total_current_year_amount,
                'total_current_month_amount'  => $total_current_month_amount,
                'due_balance'                 => $due_balance,
                'total_ride'                  => $total_ride,
                'total_today_ride_booked'     => $total_today_ride_booked,
                'maintenance_ride'            => $maintenance_ride,
            ]);
        } else{
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * Edit Dealer Page
     */
    public function edit($id)
    {
        //Dealer Edit Page
        $user = User::with([
            'companies' => function($query){
                $query->with('country');
            },
        ])->find($id);

        if($user){
            $languages = Language::all();
            $global_extras = GlobalExtra::all();
            $extras = DealerExtra::where('user_id', $id)->get();
            $dealer_extras = [];
            foreach($extras as $extra){
                $dealer_extras[$extra->global_extra_id] = $extra->type;
            }

            return view('admin.dealer.edit', [
                'user'          => $user,
                'languages'     => $languages,
                'global_extras' => $global_extras,
                'dealer_extras' => $dealer_extras,
            ]);
        } else{
            abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * Delete Dealer
     */
    public function destroy($id)
    {
        User::where('id', $id)->delete();
        Company::where('user_id', $id)->delete();

        return response()->json(['success' => true, 'message' => trans('adminMessages.door_deleted')]);
    }

    /*
     * Add Dealer Branch Page
     */
    public function addBranch($id, $company_id)
    {
        $is_user = User::with([
            'companies' => function($query) use ($id, $company_id){
                $query->where('user_id', $id);
                $query->where('id', $company_id);
            },
        ])->where('id', $id)->first();

        if($is_user){
            $setting = Setting::where('meta_key', 'default_radius_km')->select('meta_value')->first();
            $weeks = [
                'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday',
            ];
            $global_extras = GlobalExtra::all();
            $extras = DealerExtra::where('user_id', $id)->get();
            $dealer_extras = [];
            foreach($extras as $extra){
                $dealer_extras[$extra->global_extra_id] = $extra->type;
            }
            return view('admin.dealer.addBranch', [
                    'id'               => $id,
                    'company_id'       => $company_id,
                    'service_distance' => $setting->meta_value,
                    'weeks'            => $weeks,
                    'user'             => $is_user,
                    'global_extras'    => $global_extras,
                    'dealer_extras'    => $dealer_extras,
                ]
            );
        } else{
            abort(404);
        }
    }

    /*
     * Edit Dealer Branch Detail
     */
    public function editBranch($dealer_id, $branch_id)
    {
        $branch_details = CompanyAddress::with('companies', 'users', 'companyTime')
            ->where('id', $branch_id)->first();
        if($branch_details){
            $weeks = [
                'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday',
            ];
            $global_extras = GlobalExtra::all();
            $extras = BranchExtra::where('company_address_id', $branch_id)->get();
            $dealer_extras = [];
            foreach($extras as $extra){
                $dealer_extras[$extra->global_extra_id] = $extra->type;
            }

            return view('admin.dealer.editBranch', [
                'branch_id'      => $branch_id,
                'weeks'          => $weeks,
                'branch_details' => $branch_details,
                'dealer_id'      => $dealer_id,
                'global_extras'  => $global_extras,
                'dealer_extras'  => $dealer_extras,
            ]);
        } else{
            abort(404);
        }
    }

    /*
     * Add Dealer Branch Detail
     */
    public function branchAdd(Request $request)
    {
        $id = $request->input('edit_value');

        if($id == NULL){
            $validator_array = [
                'branch_logo'         => 'image|mimes:jpeg,png,jpg',
                'branch_contact_name' => 'required|max:255',
                'phone_no'            => 'required|max:255',
                'service_distance'    => 'required|max:255',
                'address'             => 'required',
                'allowed_millage'     => 'numeric',
            ];
        } else{
            $validator_array = [
                'branch_logo'         => 'image|mimes:jpeg,png,jpg',
                'branch_contact_name' => 'required|max:255',
                'phone_no'            => 'required|max:255',
                'service_distance'    => 'required|max:255',
                'address'             => 'required',
                'allowed_millage'     => 'numeric',
            ];
        }
        $validator = Validator::make($request->all(), $validator_array);
        if($validator->fails()){
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        if($id == NULL){
            $user_id = $request->input('user_id');
            $branch_logo = null;
            $country_id = $this->addCountry($request->input('country_code'), $request->input('country'));

            if($request->hasFile('branch_logo')){
                $files = $request->file('branch_logo');
                $branch_logo = ImageUploadHelper::imageUpload($files);
            }
            $company_address_id = CompanyAddress::create([
                'country_id'          => $country_id,
                'branch_name'         => $request->input('branch_name'),
                'branch_logo'         => $branch_logo,
                'bank_account'        => $request->input('bank_account'),
                'trading_license'     => $request->input('trading_license'),
                'allowed_millage'     => $request->input('allowed_millage'),
                'branch_contact_name' => $request->input('branch_contact_name'),
                'company_id'          => $request->input('company_id'),
                'user_id'             => $user_id,
                'phone_no'            => $request->input('phone_no'),
                'address'             => $request->input('address'),
                'latitude'            => $request->input('latitude'),
                'longitude'           => $request->input('longitude'),
                'service_distance'    => $request->input('service_distance'),
            ]);

            $extras = $request->input('extra');
            $extra_ids = $request->input('extra_ids');

            if($extras != NULL){
                foreach($extras as $key => $extra){
                    $branch_extra = new BranchExtra();
                    $branch_extra->company_id = $request->input('company_id');
                    $branch_extra->company_address_id = $company_address_id->id;
                    $branch_extra->global_extra_id = $extra_ids[$key];
                    $branch_extra->type = $extra;
                    $branch_extra->save();
                }
            }

            if($request->input('day_no') != NULL){
                foreach($request->input('day_no') as $key => $value){
                    $day_value = $request->input('day_value')[$key];
                    $shift_two_day_value = $request->input('shift_two_day_value')[$key];
                    if($day_value == 1 && $shift_two_day_value == 1){
                        CompanyTime::create([
                            'company_address_id' => $company_address_id->id,
                            'day_no'             => $value,
                            'sift1_start_time'   => $request->input('weekStartArray')[$key],
                            'sift1_end_time'     => $request->input('weekEndArray')[$key],
                            'sift2_start_time'   => $request->input('shiftTwoWeekStartArray')[$key],
                            'sift2_end_time'     => $request->input('shiftTwoWeekEndArray')[$key],
                        ]);
                    }
                    if($day_value == 1 && $shift_two_day_value == 0){
                        CompanyTime::create([
                            'company_address_id' => $company_address_id->id,
                            'day_no'             => $value,
                            'sift1_start_time'   => $request->input('weekStartArray')[$key],
                            'sift1_end_time'     => $request->input('weekEndArray')[$key],
                        ]);
                    }
                    if($day_value == 0 && $shift_two_day_value == 1){
                        CompanyTime::create([
                            'company_address_id' => $company_address_id->id,
                            'day_no'             => $value,
                            'sift2_start_time'   => $request->input('shiftTwoweekStartArray')[$key],
                            'sift2_end_time'     => $request->input('shiftTwoWeekEndArray')[$key],
                        ]);
                    }
                }
            }

            return response()->json(['success' => true, 'message' => trans('adminMessages.branch_inserted')]);
        } else{
            $branch_logo = null;
            User::where('id', $request->input('dealer_id'))->update([
                'name'      => $request->input('login_user_name'),
                'mobile_no' => $request->input('login_user_mobile_no'),
            ]);
            if($request->hasFile('branch_logo')){
                $files = $request->file('branch_logo');
                $branch_logo = ImageUploadHelper::imageUpload($files);
            } else{
                $branch_logo = CompanyAddress::where('id', $id)->first()->branch_logo;
            }
            CompanyAddress::where('id', $id)->update([
                'branch_name'         => $request->input('branch_name'),
                'branch_contact_name' => $request->input('branch_contact_name'),
                'branch_logo'         => $branch_logo,
                'bank_account'        => $request->input('bank_account'),
                'trading_license'     => $request->input('trading_license'),
                'allowed_millage'     => $request->input('allowed_millage'),
                'phone_no'            => $request->input('phone_no'),
                'address'             => $request->input('address'),
                'latitude'            => $request->input('latitude'),
                'longitude'           => $request->input('longitude'),
                'service_distance'    => $request->input('service_distance'),
            ]);

            CompanyTime::where('company_address_id', $id)->delete();
            if($request->input('day_no') != NULL){
                foreach($request->input('day_no') as $key => $value){
                    $day_value = $request->input('day_value')[$key];
                    if($day_value == 1){
                        CompanyTime::create([
                            'company_address_id' => $id,
                            'day_no'             => $value,
                            'start_time'         => $request->input('weekStartArray')[$key],
                            'end_time'           => $request->input('weekEndArray')[$key],
                        ]);
                    }
                }
            }
            $extras = $request->input('extra');
            $extra_ids = $request->input('extra_ids');

            if($extras != NULL){
                foreach($extras as $key => $extra){
                    $branch_old_data_check = BranchExtra::where('company_id', $request->input('company_id'))
                        ->where('company_address_id', $id)
                        ->where('global_extra_id', $extra_ids[$key])
                        ->first();
                    if($branch_old_data_check){
                        $branch_extra = BranchExtra::find($branch_old_data_check->id);
                        $branch_extra->company_id = $request->input('company_id');
                        $branch_extra->company_address_id = $id;
                        $branch_extra->global_extra_id = $extra_ids[$key];
                        $branch_extra->type = $extra;
                        $branch_extra->save();
                    }
                }
            }

            return response()->json(['success' => true, 'message' => trans('adminMessages.branch_updated')]);
        }
    }

    /*
     * Show Dealer Branch Detail
     */
    public function branchDetails($id)
    {
        $company_details = CompanyAddress::with('companies', 'users', 'companyTime', 'country')->where('id', $id)->first();


        $array['globalModalTitle'] = $company_details->companies->name;

        $array['globalModalDetails'] = '<table class="table table-bordered">';
        $array['globalModalDetails'] .= '<thead class="thead-light"><tr><th colspan="4" class="text-center">' . config('languageString.branch_contact_details') . '</th></tr></thead>';
        $array['globalModalDetails'] .= '<thead class="thead-dark"><tr><th>' . config('languageString.name') . '</th><th>' . config('languageString.mobile_no') . '</th><th>' . config('languageString.country_column') . '</th><th>' . config('languageString.branch_logo') . '</th></tr></thead>';
        $array['globalModalDetails'] .= '<tr>';
        $array['globalModalDetails'] .= '<td>' . $company_details->branch_contact_name . '</td>';
        $array['globalModalDetails'] .= '<td>' . $company_details->phone_no . '</td>';
        $array['globalModalDetails'] .= '<td>' . $company_details->country->name . '</td>';
        if(!empty($company_details->branch_logo)){
            $array['globalModalDetails'] .= '<td class="text-center"><img src="' . asset($company_details->branch_logo) . '" width="65px" height="65px"/></td>';
        }
        $array['globalModalDetails'] .= '</tr>';
        $array['globalModalDetails'] .= '<tr><thead class="thead-dark"><tr><th>' . config('languageString.branch_name') . '</th><th>' . config('languageString.bank_account') . '</th><th>' . config('languageString.trading_license') . '</th><th>' . config('languageString.allowed_millage') . '</th></tr></thead></tr>';
        $array['globalModalDetails'] .= '<td>' . $company_details->branch_name . '</td>';
        $array['globalModalDetails'] .= '<td>' . $company_details->bank_account . '</td>';
        $array['globalModalDetails'] .= '<td>' . $company_details->trading_license . '</td>';
        $array['globalModalDetails'] .= '<td>' . $company_details->allowed_millage . '</td>';
        $array['globalModalDetails'] .= '</tr>';
        $array['globalModalDetails'] .= '</table>';

        if($company_details->companyTime != NULL){
            $array['globalModalDetails'] .= '<table class="table table-bordered">';
            $array['globalModalDetails'] .= '<thead class="thead-light"><tr><th colspan="5" class="text-center">' . config('languageString.working_hour') . '</th></tr></thead>';
            $array['globalModalDetails'] .= '<thead class="thead-dark"><tr><th>' . config('languageString.day') . '</th><th>' . config('languageString.shift_1_start_time') . '</th><th>' . config('languageString.shift_1_end_time') . '</th><th>' . config('languageString.shift_2_start_time') . '</th><th>' . config('languageString.shift_2_end_time') . '</th></tr></thead>';
            foreach($company_details->companyTime as $company_times){
                $array['globalModalDetails'] .= '<tr>';
                $array['globalModalDetails'] .= '<td>' . $this->getWeekName($company_times->day_no) . '</td>';
                $array['globalModalDetails'] .= '<td>' . $company_times->sift1_start_time . '</td>';
                $array['globalModalDetails'] .= '<td>' . $company_times->sift1_end_time . '</td>';
                $array['globalModalDetails'] .= '<td>' . $company_times->sift2_start_time . '</td>';
                $array['globalModalDetails'] .= '<td>' . $company_times->sift2_end_time . '</td>';
                $array['globalModalDetails'] .= '</tr>';
            }
            $array['globalModalDetails'] .= '</table>';
        }

        return response()->json(['success' => true, 'data' => $array]);
    }

    /*
     * Get Week Name
     */
    public function getWeekName($no)
    {
        $weeks = [
            'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday',
        ];
        return $weeks[$no - 1];
    }

    /*
     * Add Country Name
     */
    public function addCountry($country_code, $country)
    {

        $country_list = Country::where('country_code', $country_code)->first();
        if($country_list){
            return $country_list->id;
        } else{
            $country_order = Country::max('id');

            $country_insert_id = Country::create([
                'country_code'  => $country_code,
                'country_order' => $country_order + 1,
            ]);

            $languages = Language::all();
            foreach($languages as $language){
                CountryTranslation::create([
                    'name'       => $country,
                    'country_id' => $country_insert_id->id,
                    'locale'     => $language->language_code,
                ]);
            }
            return $country_insert_id->id;
        }
    }

    /*
     * Change Dealer Status
     */
    public function DealerChangeStatus($id, $status)
    {
        User::where('id', $id)->update(['status' => $status]);
        return response()->json(['success' => true, 'message' => trans('adminMessages.Dealer_status')]);
    }
}
