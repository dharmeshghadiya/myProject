<?php

namespace App\Http\Controllers\Admin;

use App\BecomeADealer;
use App\User;
use App\Company;
use App\CompanyAddress;
use App\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class BecomeADealerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * View Body List
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $becomeADealers = BecomeADealer::all();
            return Datatables::of($becomeADealers)
                ->addColumn('status', function($becomeADealers){
                    $status = '';
                    if(!empty($becomeADealers->reason)){

                         $status = '<span  class=" badge badge-danger">' . config('languageString.rejected') . '</span>';
                     }
                     return $status;

                })
                ->addColumn('action', function($becomeADealers){
                    $delete_button = '<button data-id="' . $becomeADealers->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    $view_detail_button = '<button data-id="' . $becomeADealers->id . '" class="become-a-dealer-details btn  btn-secondary btn-icon" data-effect="effect-fall" data-toggle="tooltip" data-placement="top" title="' . config('languageString.view_details') . '"><i class="bx bx-bullseye font-size-16 align-middle"></i></button>';
                    $accept_button = '<button data-id="' . $becomeADealers->id . '"  data-status="Accept" class="accept-status btn  btn-info btn-icon" data-effect="effect-fall" data-toggle="tooltip" data-placement="top" title="' . config('languageString.accept') . '"><i class="bx bx-message-square-check font-size-16 align-middle"></i></button>';

                    $reject_button = '<button data-id="' . $becomeADealers->id . '" data-status="Reject"  class="reject-satatus btn btn-danger btn-icon" data-effect="effect-fall" data-toggle="tooltip" data-placement="top" title="' . config('languageString.reject') . '"><i class="bx bx-message-square-x font-size-16 align-middle"></i></button>';


                    return '<div class="btn-icon-list">' . $view_detail_button . ' ' . $accept_button . ' ' . $reject_button . ' ' . $delete_button . '</div>';
                })
                ->rawColumns(['action','status'])
                ->make(true);
        }
        return view('admin.becomeADealer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * Add Body Page
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * Add Body Details
     */
    public function store(Request $request)
    {

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
     * Edit Body Page
     */
    public function edit($id)
    {
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
     * Body Delete
     */
    public function destroy($id)
    {
        $become = BecomeADealer::where('id', $id)->first();
        unlink(url($become->logo));
        BecomeADealer::where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => trans('adminMessages.become_a_dealer_deleted')]);
    }

    public function becomeADealerChangeStatus($id, $status)
    {
        $become = BecomeADealer::where('id', $id)->first();
       
       if($status == 'Accept'){
        $insert_id = User::create([
                    'name'         => $become->name,
                    'email'        => $become->email,
                    'country_code' => $become->country_code,
                    'mobile_no'    => $become->mobile_number,
                    'panel_mode'   => 1,
                    'locale'       => 'en',
                    'user_type'    => 'company',
                    'status'       => 'Active',
                    'password'     => $become->password,
                ]);
        $company =  Company::create([

                'user_id' => $insert_id->id,
                'name' => $become->business_name,
                'contact_name' => $become->name,
                'email' => $become->email,
                'country_code' => '+'.$become->country_code,
                'mobile_no' => $become->mobile_number,
                'iban' => $become->bank_iban,
                'license_number' => $become->license_number,
                'business_number' => $become->business_number,
                'security_deposit' => $become->security_deposite,
                'license_expiry_date' => $become->license_expiry_date,
                'bank_name' => $become->bank_name,
                'bank_address' => $become->bank_address,
                'bank_contact_number' => $become->bank_contact_number,
                'beneficiary_name' => $become->beneficiary_name,
                'bank_code' => $become->bank_code,
                'country_id' => 12,
                'commission_percentage' => 20,
                'dealer_logo'=>$become->logo,
                'trade_license_image'=>$become->trade_license_doc,

                ]);

                BecomeADealer::where('id', $id)->delete();
        }else{
            BecomeADealer::where('id', $id)->update(['reason' => $status]);
        }

        return response()->json(['success' => true, 'message' => trans('adminMessages.become_a_dealer_status')]);
    }

    public function becomeAdealerDetails($id)
    {
        $becomeAdealer = BecomeADealer::where('id', $id)->first();

        $array['globalModalTitle'] = config('languageString.become_a_dealer') . ': ' . $becomeAdealer->name . ' | ' . $becomeAdealer->business_name;

        $array['globalModalDetails'] = '<table class="table table-bordered">';
        $array['globalModalDetails'] .= '<thead class="thead-light"><tr><th colspan="3" class="text-center">' . config('languageString.business_details') . '</th></tr></thead>';
        $array['globalModalDetails'] .= '<thead class="thead-dark"><tr><th>' . config('languageString.business_name') . '</th><th>' . config('languageString.business_number') . '
        </th><th>' . config('languageString.address') . '</th></tr></thead>';
        $array['globalModalDetails'] .= '<tr>';
        $array['globalModalDetails'] .= '<td>' . $becomeAdealer->business_name . '</td>';
        $array['globalModalDetails'] .= '<td>' . $becomeAdealer->business_number . '</td>';
        $array['globalModalDetails'] .= '<td>' . $becomeAdealer->address . '</td>';
        $array['globalModalDetails'] .= '</tr>';
        $array['globalModalDetails'] .= '</table>';

        if(!empty($becomeAdealer->logo)){
            $array['globalModalDetails'] .= '<table class="table table-bordered">';
            $array['globalModalDetails'] .= '<thead class="thead-light"><tr><th colspan="3" class="text-center">' . config('languageString.dealer_logo') . '</th></tr></thead>';
            $array['globalModalDetails'] .= '</table>';
            $array['globalModalTitle'] .= config('languageString.dealer');
            $url = asset($becomeAdealer->logo);

            $array['globalModalDetails'] .= "<img src='" . $url . "' />";

        }

        // contact Details
        $array['globalModalDetails'] .= '<table class="table table-bordered">';
        $array['globalModalDetails'] .= '<thead class="thead-light"><tr><th colspan="6" class="text-center">' . config('languageString.contact_details') . '</th></tr></thead>';
        $array['globalModalDetails'] .= '<thead class="thead-dark"><tr><th>' . config('languageString.name') . '</th><th>' . config('languageString.mobile_no') . '
        </th><th>' . config('languageString.email') . '</th><th>' . config('languageString.security_deposit') . '</th><th>' . config('languageString.license_umber') . '</th><th>' . config('languageString.license_expiry_date') . '</th></tr></thead>';
        $array['globalModalDetails'] .= '<tr>';
        $array['globalModalDetails'] .= '<td>' . $becomeAdealer->name . '</td>';
        $array['globalModalDetails'] .= '<td>' . $becomeAdealer->country_code . ' ' . $becomeAdealer->mobile_number . '</td>';
        $array['globalModalDetails'] .= '<td>' . $becomeAdealer->email . '</td>';
        $array['globalModalDetails'] .= '<td>' . $becomeAdealer->security_deposite . '</td>';
        $array['globalModalDetails'] .= '<td>' . $becomeAdealer->license_number . '</td>';
        $array['globalModalDetails'] .= '<td>' . $becomeAdealer->license_expiry_date . '</td>';
        $array['globalModalDetails'] .= '</tr>';
        $array['globalModalDetails'] .= '</table>';
        // bank details
        $array['globalModalDetails'] .= '<br><table class="table table-bordered">';
        $array['globalModalDetails'] .= '<thead class="thead-light"><tr><th colspan="6" class="text-center">' . config('languageString.bank_details') . '</th></tr></thead>';
        $array['globalModalDetails'] .= '<thead class="thead-dark"><tr><th>' . config('languageString.bank_name') . '</th><th>' . config('languageString.bank_address') . '
        </th><th>' . config('languageString.bank_contact_number') . '</th><th>' . config('languageString.beneficiary_name') . '</th><th>' . config('languageString.bank_code') . '</th><th>' . config('languageString.bank_iban') . '</th></tr></thead>';
        $array['globalModalDetails'] .= '<tr>';
        $array['globalModalDetails'] .= '<td>' . $becomeAdealer->bank_name . '</td>';
        $array['globalModalDetails'] .= '<td>' . $becomeAdealer->bank_address . '</td>';
        $array['globalModalDetails'] .= '<td>' . $becomeAdealer->bank_contact_number . '</td>';
        $array['globalModalDetails'] .= '<td>' . $becomeAdealer->beneficiary_name . '</td>';
        $array['globalModalDetails'] .= '<td>' . $becomeAdealer->bank_code . '</td>';
        $array['globalModalDetails'] .= '<td>' . $becomeAdealer->bank_iban . '</td>';
        $array['globalModalDetails'] .= '</tr>';
        $array['globalModalDetails'] .= '</table>';

        if(!empty($becomeAdealer->trade_license_doc)){
            $array['globalModalDetails'] .= '<table class="table table-bordered">';
            $array['globalModalDetails'] .= '<thead class="thead-light"><tr><th colspan="3" class="text-center">' . config('languageString.trade_license_doc') . '</th></tr></thead>';
            $array['globalModalDetails'] .= '</table>';

            $url = asset($becomeAdealer->trade_license_doc);

            $array['globalModalDetails'] .= "<img src='" . $url . "' />";
        }

        return response()->json(['success' => true, 'data' => $array]);
    }
}
