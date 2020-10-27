<?php

namespace App\Http\Controllers\Dealer;

use App\Models\Booking;
use App\Models\Commission;
use App\Models\Company;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $company = Company::where('user_id', Auth::user()->id)->first();
        if($company){
            session(['is_owner' => 1]);
            session(['company_id' => $company->id]);
        } else{
            session(['is_owner' => 0]);
            session(['company_id' => 0]);
        }
        $total_current_year_booking = Booking::where('company_id', $company->id)
            ->whereYear('created_at', date('Y'))
            ->count();
        $total_current_month_booking = Booking::where('company_id', $company->id)
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->count();
        $total_current_year_amount = Commission::where('company_id', $company->id)
            ->whereYear('created_at', date('Y'))
            ->where('type', 'Credit')
            ->sum('commission_amount');
        $total_current_month_amount = Commission::where('company_id', $company->id)
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->where('type', 'Credit')
            ->sum('commission_amount');
        $credit_balance = Commission::where('company_id', $company->id)
            ->where('type', 'Credit')
            ->sum('commission_amount');

        $debit_balance = Commission::where('company_id', $company->id)
            ->where('type', 'Debit')
            ->sum('commission_amount');

        $due_balance = $credit_balance - $debit_balance;
        $total_today_ride_booked = Booking::where('company_id', $company->id)
            ->where('created_at', Carbon::today())
            ->count();

        $total_ride = Vehicle::where('company_id', $company->id)
            ->count();

        $maintenance_ride = Vehicle::where('company_id', $company->id)
            ->where('status', 'InActive')
            ->count();

        return view('dealer.admin.index', [
            'total_current_year_booking'  => $total_current_year_booking,
            'total_current_month_booking' => $total_current_month_booking,
            'total_current_year_amount'   => $total_current_year_amount,
            'total_current_month_amount'  => $total_current_month_amount,
            'due_balance'                 => $due_balance,
            'total_ride'                  => $total_ride,
            'total_today_ride_booked'     => $total_today_ride_booked,
            'maintenance_ride'            => $maintenance_ride,
        ]);
    }

    public function changeThemes($id)
    {
        User::where('id', Auth::user()->id)->update(['panel_mode' => $id]);
        return redirect()->route('admin::admin');
    }

    public function changeThemesMode($local)
    {
        User::where('id', Auth::user()->id)->update(['locale' => $local]);
        return redirect()->route('admin::admin');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('admin/login');
    }
}
