<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Booking;
use App\Commission;
use App\Company;
use App\BookingDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CommissionsController extends Controller
{
    public function index()
    {
        return view('admin.commissions.index');
    }

    public function getCommission(Request $request)
    {
        if($request->ajax()){
            //DB::enableQueryLog();
            $companies = Company::get();
            // dd(DB::getQueryLog());
            return Datatables::of($companies)
                ->addColumn('action', function($companies){
                    $past_transaction_button = '<a href="' . route('admin::past-transaction', [$companies->id]) . '" class="btn btn-secondary btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.view_past_transaction') . '"><i class="bx bx-bullseye font-size-16 align-middle"></i></a>';
                    $transfer_amount = '<button data-id="' . $companies->id . '" data-name="' . $companies->name . '" class="add-transfer btn btn-warning btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.transfer_amount') . '"><i class="bx bx-dollar font-size-16 align-middle"></i></button>';
                    return '<div class="btn-icon-list">' . $transfer_amount . ' ' . $past_transaction_button . '</div>';
                })
                ->addColumn('due_balance', function($companies){
                    $total_credit = $this->getCreditBalance($companies->id);
                    $total_debit = $this->getDebitBalance($companies->id);
                    $final_balance = $total_credit;
                    if($total_credit > 0 && $total_debit > 0){
                        $final_balance = $total_credit - $total_debit;
                    }
                    return "$" . $final_balance;

                })
                ->addColumn('company_name', function($companies){
                    return $companies->name;
                })
                ->addColumn('transferred_balance', function($companies){
                    return "$" . $this->getDebitBalance($companies->id);
                })
                ->addColumn('last_transfer_date', function($companies){
                    return $this->lastTransferDate($companies->id);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getCreditBalance($company_id)
    {
        return Commission::where('company_id', $company_id)
            ->where('type', 'Credit')
            ->sum('commission_amount');
    }

    public function getDebitBalance($company_id)
    {
        return Commission::where('company_id', $company_id)
            ->where('type', 'Debit')
            ->sum('commission_amount');
    }

    public function lastTransferDate($company_id)
    {
        $response = Commission::where('company_id', $company_id)
            ->where('type', 'Debit')
            ->select('created_at')
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->first();
        if($response){
            return $response->created_at;
        } else{
            return '';
        }
    }

    public function pastTransactionView($id)
    {
        $company = Company::findOrFail($id);
        return view('admin.commissions.pastTransaction', [
            'company' => $company,
        ]);
    }

    public function getPastTransaction(Request $request, $id)
    {
        if($request->ajax()){
            //DB::enableQueryLog();
            $commissions = Commission::where('company_id', $id)->orderBy('id', 'DESC')->get();
            // dd(DB::getQueryLog());
            return Datatables::of($commissions)
                ->addColumn('id', function($commissions){
                    if($commissions->booking_id != null){
                        $booking_details = Booking::where('id', $commissions->booking_id)->select('transaction_id')->first();
                        if($booking_details){
                            return $booking_details->transaction_id;
                        } else{
                            return '';
                        }
                    }
                })
                ->addColumn('action', function($commissions){
                    if($commissions->booking_id != null){
                        $booking_details_button = '<button data-id="' . $commissions->booking_id . '" class="booking-details btn btn-secondary btn-icon" data-effect="effect-fall" data-toggle="tooltip" data-placement="top" title="' . config('languageString.view_details') . '"><i class="bx bx-bullseye font-size-16 align-middle"></i></button>';
                        return '<div class="btn-icon-list">' . $booking_details_button . '</div>';
                    } else{
                        return '';
                    }
                })
                ->addColumn('amount', function($commissions){
                    return "$" . $commissions->commission_amount;
                })
                ->addColumn('type', function($commissions){
                    if($commissions->type == 'Credit'){
                        return '<span class=" badge badge-success">' . config('languageString.credit') . '</span>';
                    } else{
                        return '<span class=" badge badge-danger">' . config('languageString.debit') . '</span>';
                    }
                })
                ->addColumn('date', function($commissions){
                    return date("Y-m-d H:i", strtotime($commissions->created_at));
                })
                ->addColumn('status', function($commissions){
                    if($commissions->type == 'Added'){
                        return '<span class=" badge badge-success">' . config('languageString.added') . '</span>';
                    } else if($commissions->status == 'transferred'){
                        return '<span class=" badge badge-info">' . config('languageString.transferred') . '</span>';
                    } else{
                        return '<span class=" badge badge-warning">' . $commissions->status . '</span>';
                    }
                })
                ->rawColumns(['action', 'type', 'status'])
                ->make(true);
        }
    }

    public function commissionStatusChange(Request $request)
    {
        if($request->ajax()){
            $value_id = $request->input('value_id');
            $status = $request->input('status');
            if($status == 'Transfer'){
                $status_value = 'in_transfer';
                $message = trans('adminMessages.payment_status_Transfer');
            } else{
                $status_value = 'transferred';
                $message = trans('adminMessages.payment_status_Transferred');
            }

            Commission::where('id', $value_id)->update([
                'status' => $status_value,
            ]);
            return response()->json(['success' => true, 'message' => $message]);
        } else{
            return response()->json(['success' => false, 'message' => trans('adminMessages.error')]);
        }
    }

    public function getBookingDetails(Request $request)
    {
        $value_id = $request->input('value_id');

        $booking = Booking::with('company', 'user')->where('id', $value_id)->first();

        if($booking){
            $array['globalModalTitle'] = config('languageString.booking_id') . ' -' . $value_id;
            $array['globalModalDetails'] = '<table class="table table-bordered">';
            $array['globalModalDetails'] .= '<thead class="thead-light"><tr><th colspan="4" class="text-center">' . config('languageString.booking_details') . '</th></tr></thead>';
            $array['globalModalDetails'] .= '<thead class="thead-dark"><tr><th>' . config('languageString.status') . '</th><th>' . config('languageString.start_date') . '</th><th>' . config('languageString.end_date') . '</th><th>' . config('languageString.transaction_id') . '</th></tr></thead>';
            $array['globalModalDetails'] .= '<tr>';
            $array['globalModalDetails'] .= '<td>' . $booking->status . '</td>';
            $array['globalModalDetails'] .= '<td>' . $booking->start_date . '</td>';
            $array['globalModalDetails'] .= '<td>' . $booking->end_date . '</td>';
            $array['globalModalDetails'] .= '<td>' . $booking->transaction_id . '</td>';
            $array['globalModalDetails'] .= '</tr>';
            $array['globalModalDetails'] .= '</table>';

            $array['globalModalDetails'] .= '<table class="table table-bordered">';
            $array['globalModalDetails'] .= '<thead class="thead-light"><tr><th colspan="2" class="text-center">' . config('languageString.pricing') . '</th></tr></thead>';
            $array['globalModalDetails'] .= '<tr>';
            $array['globalModalDetails'] .= '<td class="text-right">' . config('languageString.total_car_rent') . '</td>';
            $array['globalModalDetails'] .= '<td class="text-right">$' . $booking->total_day_rent . '</td>';
            $array['globalModalDetails'] .= '</tr>';

            $extra_service_sum = BookingDetails::where('booking_id', $value_id)->sum('amount');
            $array['globalModalDetails'] .= '<tr>';
            $array['globalModalDetails'] .= '<td class="text-right">' . config('languageString.extra_service') . '</td>';
            $array['globalModalDetails'] .= '<td class="text-right">$' . $extra_service_sum . '</td>';
            $array['globalModalDetails'] .= '</tr>';

            $array['globalModalDetails'] .= '<tr>';
            $array['globalModalDetails'] .= '<td class="text-right">' . config('languageString.total') . '</td>';
            $array['globalModalDetails'] .= '<td class="text-right">$' . $booking->sub_total . '</td>';
            $array['globalModalDetails'] .= '</tr>';

            $commission = Commission::where('booking_id', $value_id)->select('commission_percentage', 'commission_amount')->first();
            $commission_percentage = $commission->commission_percentage;

            $commission_amount = ($booking->sub_total * $commission_percentage) / 100;

            $array['globalModalDetails'] .= '<tr>';
            $array['globalModalDetails'] .= '<td class="text-right">' . config('languageString.commissions') . ' (' . $commission_percentage . '%)</td>';
            $array['globalModalDetails'] .= '<td class="text-right">- $' . $commission_amount . '</td>';
            $array['globalModalDetails'] .= '</tr>';

            $array['globalModalDetails'] .= '<tr>';
            $array['globalModalDetails'] .= '<td class="text-right">' . config('languageString.final_amount') . '</td>';
            $array['globalModalDetails'] .= '<td class="text-right">$' . $commission->commission_amount . '</td>';
            $array['globalModalDetails'] .= '</tr>';

            $array['globalModalDetails'] .= '</table>';


        } else{
            $array['globalModalTitle'] = config('languageString.booking_id') . ' -' . $value_id;
            $array['globalModalDetails'] = config('languageString.error');
        }

        return response()->json(['success' => true, 'data' => $array]);
    }

    public function amountTransfer(Request $request)
    {
        $company_id = $request->input('company_id');
        $amount = $request->input('amount');

        $validator_array = [
            'amount' => 'required',
        ];
        $validator = Validator::make($request->all(), $validator_array);
        if($validator->fails()){
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $total_credit = $this->getCreditBalance($company_id);
        $total_debit = $this->getDebitBalance($company_id);

        $final_balance = $total_credit - $total_debit;
        if($final_balance >= $amount){
            Commission::create([
                'company_id'        => $company_id,
                'commission_amount' => $amount,
                'type'              => 'Debit',
                'status'            => 'transferred',
            ]);
            return response()->json(['success' => true, 'message' => config('languageString.amount_added')]);
        } else{
            return response()->json(['success' => false, 'message' => config('languageString.not_insufficient_balance')]);
        }

    }
}
