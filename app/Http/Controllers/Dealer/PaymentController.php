<?php

namespace App\Http\Controllers\Dealer;

use App\Company;
use App\CompanyAddress;
use App\Booking;
use App\Commission;
use App\BookingDetails;
use App\Language;
use App\Setting;
use App\User;
use App\UserProfile;
use App\CountryTranslation;
use App\Country;
use App\CountryCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PaymentController extends Controller
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
            $payment = Commission::where('company_id', Session('company_id'))
                ->where('booking_id',NULL)
                ->get();
            // dd(DB::getQueryLog());

            return Datatables::of($payment)
                ->addIndexColumn()
                ->addColumn('date', function($payment){
                    $date = $payment->created_at;
                    return $date;
                })
                ->addColumn('amount', function($payment){
                    return '$' . $payment->commission_amount;
                })
                ->addColumn('type', function($payment){
                    if($payment->type == 'Credit'){
                        return "<span class='text-danger'>" . config('languageString.debit') . '</span>';
                    } else{
                        return "<span class='text-success'>" . config('languageString.credit') . '</span>';
                    }
                })
                ->addColumn('status', function($payment){
                    if($payment->status == 'Added'){
                        $status = '<span class=" badge badge-primary">'.config('languageString.added').'</span>';
                    }
                    if($payment->status == 'withdraw'){
                        $status = '<span  class=" badge badge-info">'.config('languageString.withdraw').'</span>';
                    }
                    if($payment->status == 'in_transfer'){
                        $status = '<span  class=" badge badge-info">'.config('languageString.in_transfer').'</span>';
                    }
                    if($payment->status == 'transferred'){
                        $status = '<span  class=" badge badge-info">'.config('languageString.transferred').'</span>';
                    }
                    return $status;
                })
                ->rawColumns(['status', 'type'])
                ->make(true);
        }

        $total_credit = $this->totalCredit();
        $total_debit = $this->totalDebit();
        $total_balance = $total_credit - $total_debit;
        return view('dealer.payment.index', ['total_balance' => $total_balance]);
    }

    public function totalCredit()
    {
        return Commission::where('company_id', Session('company_id'))
            ->where('type', 'Credit')->sum('commission_amount');
    }

    public function totalDebit()
    {
        return Commission::where('company_id', Session('company_id'))
            ->where('type', 'Debit')->sum('commission_amount');
    }

    public function withdraw()
    {
        $total_credit = $this->totalCredit();
        $total_debit = $this->totalDebit();
        $total_balance = $total_credit - $total_debit;

        if($total_balance > 0){
            Commission::create([
                'company_id'        => Session('company_id'),
                'commission_amount' => $total_balance,
                'type'              => 'Debit',
                'status'            => 'withdraw',
            ]);

            return response()->json(['success' => true, 'message' => trans('adminMessages.withdraw_request_sent'), 'total_balance' => 0]);
        } else{
            return response()->json(['success' => false, 'message' => trans('adminMessages.not_available_balance'), 'total_balance' => 0]);
        }

    }


}
