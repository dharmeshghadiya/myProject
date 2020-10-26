<?php

namespace App\Http\Controllers\Dealer;

use App\Booking;
use App\BookingDetails;
use App\Body;
use App\Brand;
use App\Color;
use App\BrandModel;
use App\Country;
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
use App\VehicleExtra;
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


                    $view_detail_button = '<button data-id="' . $bookings->id . '" class="booking-details btn btn-secondary btn-icon" data-effect="effect-fall" data-toggle="tooltip" data-placement="top" title="' . config('languageString.view_details') . '"><i class="bx bx-bullseye font-size-16 align-middle"></i></button>';

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
            'countries'        => Country::all(),
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
            if(!empty($commission->commission_amount)){
                $array['globalModalDetails'] .= '<td class="text-right">$' . $commission->commission_amount . '</td>';
            }else{
                $array['globalModalDetails'] .= '<td class="text-right">$0</td>';
            }
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

        $start_date = Carbon::parse($pick_up_date)->format("Y-m-d H:i:s");
        $end_date = Carbon::parse($return_date)->format("Y-m-d H:i:s");
        // dd($start_date);

        $no_of_days = Carbon::parse($pick_up_date)->diffInDays($end_date);
        $no_of_hour = Carbon::parse($pick_up_date)->diffInHours($end_date);

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
                $response = $this->inRideBetween($start_date, $end_date, $vehicle->id);
                $not_availability_response = $this->inRideAvailability($start_date, $end_date, $vehicle->id);

                if($response == 0 && $not_availability_response == 0){

                    $name = $vehicle->ryde->brand->name . ' ' . $vehicle->trim . ' ' . $vehicle->ryde->modelYear->name;
                    //echo '<option value="' . $vehicle->id . '">' . $name . '(' . $vehicle->hourly_amount . ')' . '</option>';
                    $vehicleArray[$i]['vehicle_id'] = $vehicle->id;
                    $vehicleArray[$i]['company_id'] = $vehicle->company_id;
                    $vehicleArray[$i]['country_id'] = $vehicle->companyAddress->country_id;
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
                    $amount_array['daily_amount'] = $vehicle->daily_amount;
                    $amount_array['hourly_amount'] = $vehicle->hourly_amount;
                    $amount_array['weekly_amount'] = $vehicle->weekly_amount;
                    $amount_array['monthly_amount'] = $vehicle->monthly_amount;
                    $vehicleArray[$i]['price'] = $this->getRatePerTime($no_of_days, $no_of_hour, $amount_array);

                    $i++;
                }
            }

            if($vehicleArray){
                return view("dealer.booking.vehicleShow", [
                    'vehicles' => $vehicleArray,
                ]);

            } else{
                return response()->json(['success' => false, 'message' => config('languageString.ryde_not_available_this_date')]);
            }
        }
    }


    public function inRideBetween($start_date, $end_date, $vehicle_id)
    {
        // DB::enableQueryLog();
        return Booking::where(function($query) use ($start_date, $end_date){
            $query->whereBetween('start_date', [$start_date, $end_date]);
            $query->OrWhereBetween('end_date', [$start_date, $end_date]);
        })->where('vehicle_id', $vehicle_id)
            ->where('status','!=', 'Rejected')
            ->where('status','!=', 'Review')
            ->where('status','!=', 'Cancelled')
            ->where('status','!=', 'Completed')->count();
        // dd(DB::getQueryLog());

    }

    public function inRideAvailability($start_date, $end_date, $vehicle_id)
    {
        return VehicleNotAvailable::where(function($query) use ($start_date, $end_date){
            $query->whereBetween('start_date', [$start_date, $end_date]);
            $query->OrWhereBetween('end_date', [$start_date, $end_date]);
        })->where('vehicle_id', $vehicle_id)->count();

    }

    public function getVehicleDetails(Request $request)
    {
        $country_id = $request->country_id;
        $company_address_id = $request->company_address_id;
        $vehicle_id = $request->value_id;
        $booking_date = explode(" - ", $request->booking_date);

        if($company_address_id == NULL){
            return response()->json(['success' => false, 'message' => config('languageString.please_select_branch')]);
        }
        if($vehicle_id == NULL){
            return response()->json(['success' => false, 'message' => config('languageString.please_select_ryde')]);
        }
        if($booking_date == NULL){
            return response()->json(['success' => false, 'message' => config('languageString.please_select_date')]);
        }

        $pick_up_date = $booking_date[0];
        $return_date = $booking_date[1];

        $start_date = Carbon::parse($pick_up_date);
        $end_date = Carbon::parse($return_date);

        $no_of_days = $start_date->diffInDays($end_date);
        $no_of_hour = $start_date->diffInHours($end_date);


        $vehicles = Vehicle::with([
            'vehicleExtra' => function($query){
                $query->with('extra');
            },
        ])->where(['id' => $vehicle_id])->first();


        $data['modalData'] = '<form id="bookingForm" method="post">';
        $data['modalData'] .= '<input type ="hidden"  id="no_days" value = "' . $no_of_days . '">';
        $data['modalData'] .= '<input type ="hidden"  id="modal_company_address_id" value = "' . $request->company_address_id . '">';
        $data['modalData'] .= '<input type ="hidden"  id="vehicle_id" value = "' . $vehicle_id . '">';
        $data['modalData'] .= '<input type ="hidden"  id="start_date" value = "' . $start_date . '">';
        $data['modalData'] .= '<input type ="hidden"  id="end_date" value = "' . $end_date . '">';
        $data['modalData'] .= '<input type ="hidden"  id="no_of_hour" value = "' . $no_of_hour . '">';
        $data['modalData'] .= '<table class="table table-bordered">';

        if($vehicles->vehicleExtra != NULL){
            foreach($vehicles->vehicleExtra as $extra){
                $data['modalData'] .= '<tr>';
                $data['modalData'] .= '<th><input type="checkbox" name="extra[]" class="extra_check_box" value="' . $extra->id . '">';
                $data['modalData'] .= '<input type ="hidden"  id="no_days' . $extra->id . '" value = "' . $extra->type . '">';
                $data['modalData'] .= '<input type ="hidden"  id="extra_type_' . $extra->id . '" value = "' . $extra->type . '">';

                $data['modalData'] .= '</th>';
                $data['modalData'] .= ' <td>' . $extra->extra->name . ' </td> ';
                $data['modalData'] .= ' <td>' . $extra->extra->description . ' </td> ';
                $data['modalData'] .= ' <td>$' . $extra->price . ' </td> ';
                $data['modalData'] .= ' <tr>';
            }
        }
        $amount_array['daily_amount'] = $vehicles->daily_amount;
        $amount_array['hourly_amount'] = $vehicles->hourly_amount;
        $amount_array['weekly_amount'] = $vehicles->weekly_amount;
        $amount_array['monthly_amount'] = $vehicles->monthly_amount;

        $get_price = $this->getRatePerTime($no_of_days, $no_of_hour, $amount_array);
        $data['modalData'] .= '<input type ="hidden"  id="total_day_rent" value = "' . $get_price[0] . '">';
        $data['modalData'] .= ' </table>';
        $data['modalData'] .= '<div class="row">';
        $data['modalData'] .= '<label for="sub_total" class="col-md-9 text-right">' . config('languageString.sub_total') . '</label>';
        $data['modalData'] .= '<div class="input-group mb-3 col-md-3">';
        $data['modalData'] .= '<div class="input-group-prepend">';
        $data['modalData'] .= '<span class="input-group-text" id="basic-addon1">$</span>';
        $data['modalData'] .= '</div>';
        $data['modalData'] .= '<input type="text" name="sub_total" id="sub_total" class="form-control text-right bg-light" value="' . $get_price[0] . '" readonly>';
        $data['modalData'] .= '</div>';
        $data['modalData'] .= ' </div>';
        $data['modalData'] .= ' </div>';

        $data['modalData'] .= '<div class="row">';
        $data['modalData'] .= '<label for="extra" class="col-md-9 text-right">' . config('languageString.extra') . '</label>';
        $data['modalData'] .= '<div class="input-group mb-3 col-md-3">';
        $data['modalData'] .= '<div class="input-group-prepend">';
        $data['modalData'] .= '<span class="input-group-text" id="basic-addon1">$</span>';
        $data['modalData'] .= '</div>';
        $data['modalData'] .= '<input type="text" name="extra" id="extra" class="form-control text-right bg-light" value="0" readonly>';
        $data['modalData'] .= '</div>';
        $data['modalData'] .= ' </div>';
        $data['modalData'] .= ' </div>';

        $country = Country::where('id', $country_id)->first();


        if($country->tax_percentage > 0){
            $tax = ($get_price[0] * $country->tax_percentage) / 100;
            $data['modalData'] .= '<div class="row">';
            $data['modalData'] .= '<label for="total_tax" class="col-md-9 text-right">' . $country->tax_name . '.(' . $country->tax_percentage . '%)</label>';
            $data['modalData'] .= '<div class="input-group mb-3 col-md-3">';
            $data['modalData'] .= '<div class="input-group-prepend">';
            $data['modalData'] .= '<span class="input-group-text" id="basic-addon1">$</span>';
            $data['modalData'] .= '</div>';
            $data['modalData'] .= '<input type="hidden" name="tax_percentage" id="tax_percentage" class="form-control text-right bg-light" value="' . $country->tax_percentage . '">';
            $data['modalData'] .= '<input type="text" name="total_tax" id="total_tax" class="form-control text-right bg-light" value="' . $tax . '" readonly>';
            $data['modalData'] .= '</div>';
            $data['modalData'] .= ' </div>';
            $data['modalData'] .= ' </div>';
        } else{
            $tax = 0;
            $data['modalData'] .= '<input type="hidden" name="tax_percentage" id="tax_percentage" class="form-control text-right bg-light" value="0">';
            $data['modalData'] .= '<input type="hidden" name="total_tax" id="total_tax" class="form-control text-right bg-light" value="0" readonly>';
        }
        $final_total = $tax + $get_price[0];

        $data['modalData'] .= '<div class="row">';
        $data['modalData'] .= '<label for="total_amount" class="col-md-9 text-right">' . config('languageString.total') . '</label>';
        $data['modalData'] .= '<div class="input-group mb-3 col-md-3">';
        $data['modalData'] .= '<div class="input-group-prepend">';
        $data['modalData'] .= '<span class="input-group-text" id="basic-addon1">$</span>';
        $data['modalData'] .= '</div>';
        $data['modalData'] .= '<input type="text" name="total_amount" id="total_amount" class="form-control text-right bg-light" value="' . $final_total . '" readonly>';
        $data['modalData'] .= '</div>';
        $data['modalData'] .= ' </div>';
        $data['modalData'] .= ' </form>';

        return response()->json(['success' => true, 'data' => $data]);


    }

    public function addBooking(Request $request)
    {
        $validator_array = [
            'name'           => 'required',
            'email'          => 'required|email',
            'country_code'   => 'required',
            'mobile_no'      => 'required',
            'sub_total'      => 'required',
            'total_amount'   => 'required',
            'total_tax'      => 'required',
            'tax_percentage' => 'required',
            'vehicle_id'     => 'required',
            'start_date'     => 'required',
            'end_date'       => 'required',
            'total_day_rent' => 'required',
        ];
        $validator = Validator::make($request->all(), $validator_array);
        if($validator->fails()){
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $response = $this->inRideBetween($request->input('start_date'), $request->input('end_date'), $request->input('vehicle_id'));
        $not_availability_response = $this->inRideAvailability($request->input('start_date'), $request->input('end_date'), $request->input('vehicle_id'));
        if($response == 0 && $not_availability_response == 0){

            $user = User::where('email', $request->input('email'))->first();
            if($user){
                $user_id = $user->id;
            } else{
                $user = User::create([
                    'email'        => $request->input('email'),
                    'country_code' => $request->input('country_code'),
                    'mobile_no'    => $request->input('mobile_no'),
                ]);

                $user_id = $user->id;
            }


            $company_address = CompanyAddress::where('id', $request->input('company_address_id'))->first();

            $booking = new Booking();
            $booking->user_id = $user_id;
            $booking->company_id = $company_address->company_id;
            $booking->company_address_id = $request->input('company_address_id');
            $booking->vehicle_id = $request->input('vehicle_id');
            $booking->start_date = $request->input('start_date');
            $booking->end_date = $request->input('end_date');
            $booking->total_day_rent = $request->input('sub_total');
            $booking->sub_total = $request->input('total_amount');
            $booking->pick_up_location = $company_address->address;
            $booking->pick_up_latitude = $company_address->latitude;
            $booking->pick_up_longitude = $company_address->longitude;
            $booking->tax_percentage = $request->input('tax_percentage');
            $booking->total_tax = $request->input('total_tax');
            $booking->status = 'Booked';
            $booking->save();


            if($request->input('extra_val') != NULL){

                foreach($request->input('extra_val') as $key => $value){
                    $get_extra_value = VehicleExtra::where('id', $value)->first();
                    BookingDetails::create([
                        'booking_id'       => $booking->id,
                        'extra_service_id' => $value,
                        'type'             => $get_extra_value->type,
                        'amount'           => $get_extra_value->price,
                    ]);
                }
            }
            return response()->json(['success' => true, 'message' => config('languageString.booking_successfully')]);
        } else{
            return response()->json(['success' => false, 'message' => config('languageString.ryde_not_available_this_date')]);
        }
    }

    public function getFinalAmount(Request $request)
    {
        $extra_val = $request->input('extra_val');
        $no_days = $request->input('no_days');
        $no_of_hour = $request->input('no_of_hour');
        $tax_percentage = $request->input('tax_percentage');
        $sub_total = $request->input('sub_total');

        if($no_days == 0){
            $no_days = 1;
        }
        $vehicle_extra = vehicleExtra::whereIn('id', $extra_val)->get();
        $final_extra_price = 0;
        foreach($vehicle_extra as $value){
            //var_dump($value->price);
            if($value->type == 1){
                $final_extra_price = $final_extra_price + $no_days * $value->price;
            } else{
                $final_extra_price = $final_extra_price + $value->price;
            }
        }
        $total_tax = ($sub_total + $final_extra_price) * $tax_percentage / 100;
        $total = $total_tax + $sub_total + $final_extra_price;
        return response()->json([
            'success'           => true,
            'final_extra_price' => $final_extra_price,
            'total_tax'         => $total_tax,
            'total'             => $total,
        ]);

    }

    function getRatePerTime($no_of_days, $no_of_hour, $amount_array)
    {

        $hourly_amount = $amount_array['hourly_amount'];

        if($no_of_days == 0 && $hourly_amount > 0){
            if($no_of_hour == 0){
                $no_of_hour = 1;
            }
            return [$no_of_hour * $hourly_amount, config('languageString.hour')];

        } else{

            $amount = $amount_array['daily_amount'];

            if($no_of_days > 30){
                $amount = $amount_array['monthly_amount'];
            } else if($no_of_days > 7){
                $amount = $amount_array['weekly_amount'];
            }
            if($no_of_days == 0){
                $no_of_days = 1;
            }
            return [$amount * $no_of_days, config('languageString.days')];
        }
    }


}
