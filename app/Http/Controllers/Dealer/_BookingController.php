<?php

namespace App\Http\Controllers\Dealer;

use App\Booking;
use App\BookingDetails;
use App\Body;
use App\Brand;
use App\Color;
use App\Country;
use App\BrandModel;
use App\ModelYear;
use App\Category;
use App\CategoryVehicle;
use App\Company;
use App\CompanyAddress;
use App\Door;
use App\Engine;
use App\Option;
use App\Fuel;
use App\Gearbox;
use App\Insurance;
use App\Language;
use App\Ryde;
use App\RydeFeature;
use App\RydeInstance;
use App\Commission;
use App\VehicleOption;
use App\RydeTranslation;
use App\VehicleNotAvailable;
use App\User;
use App\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BookingController extends Controller
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
            $bookings = Booking::where('company_id', Session('company_id'))->with('company', 'companyAddress', 'user')->get();
            //dd(DB::getQueryLog());
            return Datatables::of($bookings)
                ->addColumn('id', function($bookings){
                    return $bookings->transaction_id;
                })
                ->addColumn('company_name', function($bookings){
                    return $bookings->company->name;
                })
                ->addColumn('address', function($bookings){
                    return $bookings->companyAddress->address;
                })
                ->addColumn('user_name', function($bookings){
                    return $bookings->user->name;
                })
                ->addColumn('mobile_no', function($bookings){
                    return $bookings->user->mobile_no;
                })
                ->addColumn('booking_date', function($bookings){
                    return Carbon::parse($bookings->start_date)->format('d-m-Y') . '  -  ' . Carbon::parse($bookings->end_date)->format('d-m-Y');
                })
                ->addColumn('total_day_rent', function($bookings){
                    return "$" . $bookings->total_day_rent;
                })
                ->addColumn('status', function($bookings){

                    if($bookings->status == 'Booked'){
                        $status = '<span class=" badge badge-primary">' . config('languageString.booked') . '</span>';
                    }
                    if($bookings->status == 'Completed'){
                        $status = '<span  class=" badge badge-success">' . config('languageString.completed') . '</span>';
                    }
                    if($bookings->status == 'Cancelled'){
                        $status = '<span  class=" badge badge-danger">' . config('languageString.cancelled') . '</span>';
                    }
                    if($bookings->status == 'Review'){
                        $status = '<span  class=" badge badge-info">' . config('languageString.review') . '</span>';
                    }
                    return $status;

                })
                ->addColumn('action', function($bookings){


                    $view_detail_button = '<button data-id="' . $bookings->id . '" class="booking-details btn btn-primary btn-icon" data-effect="effect-fall" data-toggle="tooltip" data-placement="top" title="' . config('languageString.view_details') . '"><i class="bx bx-bullseye font-size-16 align-middle"></i></button>';

                    return '<div class="btn-icon-list">' . $view_detail_button . '</div>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('dealer.booking.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $companyAddresses = CompanyAddress::where('company_id', Session('company_id'))->get();
        return view('dealer.booking.create', [

            'companyAddresses' => $companyAddresses,

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
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
     */
    public function destroy($id)
    {

    }


    public function BookingDetails($id)
    {
        $value_id = $id;

        $booking = Booking::with([
            'company', 'user',
            'vehicles'          => function($query){
                $query->with([
                    'ryde' => function($q){
                        $q->with('brand', 'modelYear', 'color', 'rydeInstance');
                    },
                ], 'vehicleOptions');
            }, 'companyAddress' => function($query){
                $query->with('country');
            },
        ])->where('id', $value_id)->first();

        if($booking){
            $array['globalModalTitle'] = config('languageString.booking_id') . ' -' . $booking->transaction_id;

            $array['globalModalDetails'] = '<table class="table table-bordered">';
            $array['globalModalDetails'] .= '<thead class="thead-light"><tr><th colspan="4" class="text-center">' . config('languageString.booking_details') . ' : ' . $booking->company->name . ' | ' . $booking->companyAddress->address . ' | ' . $booking->user->name . ' | ' . $booking->user->mobile_no . '</th></tr></thead>';
            $array['globalModalDetails'] .= '<thead class="thead-dark"><tr><th>' . config('languageString.status') . '</th><th>' . config('languageString.start_date') . '</th><th>' . config('languageString.end_date') . '</th><th>' . config('languageString.transaction_id') . '</th></tr></thead>';
            $array['globalModalDetails'] .= '<tr>';
            $array['globalModalDetails'] .= '<td>' . $booking->status . '</td>';
            $array['globalModalDetails'] .= '<td>' . $booking->start_date . '</td>';
            $array['globalModalDetails'] .= '<td>' . $booking->end_date . '</td>';
            $array['globalModalDetails'] .= '<td>' . $booking->transaction_id . '</td>';
            $array['globalModalDetails'] .= '</tr>';
            $array['globalModalDetails'] .= '</table>';


            $array['globalModalDetails'] .= '<table class="table table-bordered">';
            $array['globalModalDetails'] .= '<thead class="thead-light"><tr><th colspan="6" class="text-center">' . config('languageString.ryde_details') . ': ' . $booking->vehicles->ryde->brand->name . ' | ' . $booking->vehicles->ryde->name . ' | ' . $booking->vehicles->ryde->modelYear->name . '</th></tr></thead>';
            $array['globalModalDetails'] .= '<thead class="thead-dark"><tr><th>' . config('languageString.color') . '</th><th>' . config('languageString.hourly_amount') . '
        </th><th>' . config('languageString.daily_amount') . '</th><th>' . config('languageString.weekly_amount') . '</th><th>' . config('languageString.monthly_amount') . '</th></tr></thead>';
            $array['globalModalDetails'] .= '<tr>';
            $array['globalModalDetails'] .= '<td>' . $booking->vehicles->ryde->color->name . '</td>';
            $array['globalModalDetails'] .= '<td>' . '$ ' . $booking->vehicles->hourly_amount . '</td>';
            $array['globalModalDetails'] .= '<td>' . '$ ' . $booking->vehicles->daily_amount . '</td>';
            $array['globalModalDetails'] .= '<td>' . '$ ' . $booking->vehicles->weekly_amount . '</td>';
            $array['globalModalDetails'] .= '<td>' . '$ ' . $booking->vehicles->monthly_amount . '</td>';
            $array['globalModalDetails'] .= '</tr>';
            $array['globalModalDetails'] .= '</table>';

            // further booking details
            $array['globalModalDetails'] .= '<table class="table table-bordered">';
            $array['globalModalDetails'] .= '<thead class="thead-light"><tr><th colspan="4" class="text-center">' . config('languageString.booking_details') . '</th></tr></thead>';
            $array['globalModalDetails'] .= '<thead class="thead-dark"><tr><th>' . config('languageString.pick_up_location') . '</th><th>' . config('languageString.pick_up_latitude') . '</th><th>' . config('languageString.pick_up_longitude') . '</th><th>' . config('languageString.message') . '</th></tr></thead>';
            $array['globalModalDetails'] .= '<tr>';
            $array['globalModalDetails'] .= '<td>' . $booking->pick_up_location . '</td>';
            $array['globalModalDetails'] .= '<td>' . $booking->pick_up_latitude . '</td>';
            $array['globalModalDetails'] .= '<td>' . $booking->pick_up_longitude . '</td>';
            $array['globalModalDetails'] .= '<td>' . $booking->message . '</td>';
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

            $total = $booking->sub_total;
            if($booking->total_tax > 0){
                $array['globalModalDetails'] .= '<tr>';
                $array['globalModalDetails'] .= '<td class="text-right">' . $booking->companyAddress->country->tax_name . '(' . $booking->tax_percentage . ')' . '</td>';
                $array['globalModalDetails'] .= '<td class="text-right">$' . $booking->total_tax . '</td>';
                $array['globalModalDetails'] .= '</tr>';
                $total += $booking->total_tax;
            }

            $array['globalModalDetails'] .= '<tr>';
            $array['globalModalDetails'] .= '<td class="text-right">' . config('languageString.total') . '</td>';
            $array['globalModalDetails'] .= '<td class="text-right">$' . $total . '</td>';
            $array['globalModalDetails'] .= '</tr>';

            $commission = Commission::where('booking_id', $value_id)->select('commission_percentage', 'commission_amount')->first();
            if($commission){
                $commission_percentage = $commission->commission_percentage;

                $commission_amount = ($booking->sub_total * $commission_percentage) / 100;
            } else{
                $commission_percentage = 0;
                $commission_amount = 0;
            }

            $array['globalModalDetails'] .= '<tr>';
            $array['globalModalDetails'] .= '<td class="text-right">' . config('languageString.ryde_zilla_commissions') . ' (' . $commission_percentage . '%)</td>';
            $array['globalModalDetails'] .= '<td class="text-right">- $' . $commission_amount . '</td>';
            $array['globalModalDetails'] .= '</tr>';
            $array['globalModalDetails'] .= '<tr>';
            $array['globalModalDetails'] .= '<td class="text-right">' . config('languageString.adjustment') . '</td>';
            $array['globalModalDetails'] .= '<td class="text-right">$' . $booking->adjustment . '</td>';

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

    public function getVehicleForBooking(Request $request)
    {
        $company_address_id = $request->company_address_id;
        $booking_date = explode(" - ", $request->booking_date);
        if($company_address_id == NULL){
            return response()->json(['success' => false, 'message' => config('languageString.please_select_branch')]);
        }
        if($booking_date == NULL){
            return response()->json(['success' => false, 'message' => config('languageString.please_select_date')]);
        }

        $pick_up_date = $booking_date[0];
        $return_date = $booking_date[1];

        if(!empty($company_address_id)){
            $vehicles = Vehicle::where(['company_address_id' => $company_address_id])
                ->with([
                    'companyAddress', 'insurances', 'engine',
                    'fuel', 'gearbox',
                    'ryde' => function($query){
                        $query->with('brand', 'modelYear', 'color', 'rydeInstance');
                    },
                ])->get();

            $vehicleArray = [];
            $i = 0;

            foreach($vehicles as $vehicle){
                $response = $this->inRideBetween($pick_up_date, $return_date, $vehicle->id);
                $not_availability_response = $this->inRideAvailability($pick_up_date, $return_date, $vehicle->id);
                if($response == 0 && $not_availability_response == 0){
                    $vehicleArray[$i]['vehicle_id'] = $vehicle->id;
                    $vehicleArray[$i]['company_id'] = $vehicle->company_id;
                    $vehicleArray[$i]['company_address_id'] = $vehicle->id;
                    $vehicleArray[$i]['hourly_amount'] = $vehicle->hourly_amount;
                    $vehicleArray[$i]['daily_amount'] = $vehicle->daily_amount;
                    $vehicleArray[$i]['weekly_amount'] = $vehicle->weekly_amount;
                    $vehicleArray[$i]['monthly_amount'] = $vehicle->monthly_amount;
                    $vehicleArray[$i]['image'] = $vehicle->ryde->image;
                    $vehicleArray[$i]['trim'] = $vehicle->trim;
                    $vehicleArray[$i]['security_deposit'] = $vehicle->security_deposit;
                    $vehicleArray[$i]['make'] = $vehicle->ryde->brand->name;
                    $vehicleArray[$i]['model'] = $vehicle->ryde->modelYear->name;

                    $i++;
                }
            }

            if($vehicleArray){
                $view = view("dealer.booking.vehicleShow", [
                    'vehicles' => $vehicleArray,
                ])->render();

                return response()->json(['success' => true, 'data' => $view]);


            } else{
                return response()->json(['success' => false, 'message' => config('languageString . ryde_not_available_this_date')]);
            }
        }
    }


    public function inRideBetween($start_date, $end_date, $vehicle_id)
    {
        return Booking::where(function($query) use ($start_date, $end_date){
            $query->whereBetween('start_date', [$start_date, $end_date]);
            $query->OrWhereBetween('end_date', [$start_date, $end_date]);
        })->where('vehicle_id', $vehicle_id)
            ->where('status', ' != ', 'Rejected')
            ->where('status', ' != ', 'Review')
            ->where('status', ' != ', 'Cancelled')
            ->where('status', ' != ', 'Completed')->count();

    }

    public function inRideAvailability($start_date, $end_date, $vehicle_id)
    {
        return VehicleNotAvailable::where(function($query) use ($start_date, $end_date){
            $query->whereBetween('start_date', [$start_date, $end_date]);
            $query->OrWhereBetween('end_date', [$start_date, $end_date]);
        })->where('vehicle_id', $vehicle_id)->count();

    }


}
