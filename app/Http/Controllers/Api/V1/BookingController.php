<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Company;
use App\Models\Booking;
use App\Models\Commission;
use App\Models\BookingInspection;
use App\Models\BookingDetails;
use App\Models\CompanyAddress;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user_id = $user->id;

        $validator = Validator::make($request->all(), [
            'company_id'         => 'required',
            'company_address_id' => 'required',
            'vehicle_id'         => 'required',
            'start_date'         => 'required',
            'end_date'           => 'required|after_or_equal:start_date',
            'total_day_rent'     => 'required',
            'sub_total'          => 'required',
            'pick_up_location'   => 'required',
            'pick_up_latitude'   => 'required',
            'pick_up_longitude'  => 'required',
            'transaction_id'     => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 403);
        }


        $booking = new Booking();
        $booking->user_id = $user_id;
        $booking->company_id = $request->input('company_id');
        $booking->company_address_id = $request->input('company_address_id');
        $booking->vehicle_id = $request->input('vehicle_id');
        $booking->start_date = $request->input('start_date');
        $booking->end_date = $request->input('end_date');
        $booking->total_day_rent = $request->input('total_day_rent');
        $booking->sub_total = $request->input('sub_total');
        $booking->pick_up_location = $request->input('pick_up_location');
        $booking->pick_up_latitude = $request->input('pick_up_latitude');
        $booking->pick_up_longitude = $request->input('pick_up_longitude');
        $booking->transaction_id = $request->input('transaction_id');
        $booking->tax_percentage = $request->input('tax_percentage');
        $booking->total_tax = $request->input('total_tax');
        $booking->status = 'Booked';
        $booking->save();

        $company = Company::where('id', $request->input('company_id'))->select('commission_percentage')->first();
        $commission_amount = ($company->commission_percentage / 100) * $request->input('sub_total');

        $commission = new Commission();
        $commission->booking_id = $booking->id;
        $commission->company_id = $request->input('company_id');
        $commission->company_address_id = $request->input('company_address_id');
        $commission->commission_percentage = $company->commission_percentage;
        $commission->commission_amount = $request->input('sub_total') - $commission_amount;
        $commission->type = 'Credit';
        $commission->status = 'Added';
        $commission->save();

        if($request->input('extra_id') != NULL){

            foreach($request->input('extra_id') as $key => $value){
                BookingDetails::create([
                    'booking_id'       => $booking->id,
                    'extra_service_id' => $value,
                    'type'             => $request->input('extra_type')[$key],
                    'amount'           => $request->input('extra_price')[$key],
                ]);
            }
        }

        return [
            'success' => true,
            'message' => Config('languageString.ryde_bookd'),
        ];

    }

    public function extendContract(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user_id = $user->id;

        $validator = Validator::make($request->all(), [
            'booking_id'     => 'required',
            'end_date'       => 'required',
            'total_day_rent' => 'required',
            'sub_total'      => 'required',
            'transaction_id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 403);
        }

        $bookings = Booking::where('id', $request->input('booking_id'))->first();

        if($bookings){
            $booking = new Booking();
            $booking->user_id = $bookings->user_id;
            $booking->company_id = $bookings->company_id;
            $booking->company_address_id = $bookings->company_address_id;
            $booking->start_date = Carbon::parse($bookings->end_date)->addSeconds(1);
            $booking->vehicle_id = $bookings->vehicle_id;
            $booking->end_date = $request->input('end_date');
            $booking->total_day_rent = $request->input('total_day_rent');
            $booking->sub_total = $request->input('sub_total');
            $booking->total_tax = $request->input('total_tax');
            $booking->tax_percentage = $request->input('tax_percentage');
            $booking->pick_up_location = $bookings->pick_up_location;
            $booking->pick_up_latitude = $bookings->pick_up_latitude;
            $booking->pick_up_longitude = $bookings->pick_up_longitude;
            $booking->transaction_id = $request->input('transaction_id');
            $booking->parent_id = $request->input('booking_id');
            $booking->status = 'Booked';
            $booking->save();

            $company = Company::where('id', $bookings->company_id)->select('commission_percentage')->first();
            $commission_amount = ($company->commission_percentage / 100) * $request->input('sub_total');

            $commission = new Commission();
            $commission->booking_id = $booking->id;
            $commission->company_id = $bookings->company_id;
            $commission->company_address_id = $bookings->company_address_id;
            $commission->commission_percentage = $company->commission_percentage;
            $commission->commission_amount = $request->input('sub_total') - $commission_amount;
            $commission->type = 'Credit';
            $commission->status = 'Added';
            $commission->save();


            $booking_details = BookingDetails::where('booking_id', $request->input('booking_id'))->get();
            if(count($booking_details)){
                foreach($booking_details as $booking_detail){
                    BookingDetails::create([
                        'booking_id'       => $booking->id,
                        'extra_service_id' => $booking_detail->extra_service_id,
                        'type'             => $booking_detail->type,
                        'amount'           => $booking_detail->amount,
                    ]);
                }
            }

            return [
                'success' => true,
                'message' => Config('languageString.ryde_extend_successfully'),
            ];
        } else{
            return [
                'success' => true,
                'message' => Config('languageString.error'),
            ];
        }
    }

    public function getMyRide()
    {
        $user = JWTAuth::parseToken()->authenticate();

        if($user){
            $user_id = $user->id;

            $get_my_rides = Booking::with([
                'vehicles' => function($query){
                    $query->with([
                        'ryde' => function($query){
                            $query->with('brand', 'modelYear', 'color');
                        },
                    ]);
                },
            ])->where('user_id', $user_id)
                ->orderBy('id', 'DESC')
                ->paginate(25);

            if(count($get_my_rides) > 0){
                $array = [];
                $i = 0;

                foreach($get_my_rides as $get_my_ride){
                    $array[$i]['booking_id'] = $get_my_ride->id;
                    $array[$i]['vehicle_id'] = $get_my_ride->vehicle_id;
                    $array[$i]['end_date'] = $get_my_ride->end_date;
                    $array[$i]['status'] = $get_my_ride->status;
                    $array[$i]['booking_date'] = Carbon::parse($get_my_ride->start_date)->format('d') . ' - ' . Carbon::parse($get_my_ride->end_date)->format('d M y');
                    $array[$i]['amount'] = $get_my_ride->sub_total;
                    $array[$i]['total_tax'] = $get_my_ride->total_tax;
                    $array[$i]['tax_percentage'] = $get_my_ride->tax_percentage;
                    $array[$i]['trim'] = $get_my_ride->vehicles->trim;
                    $array[$i]['hourly_amount'] = $get_my_ride->vehicles->hourly_amount;
                    $array[$i]['daily_amount'] = $get_my_ride->vehicles->daily_amount;
                    $array[$i]['weekly_amount'] = $get_my_ride->vehicles->weekly_amount;
                    $array[$i]['monthly_amount'] = $get_my_ride->vehicles->monthly_amount;
                    $array[$i]['brand'] = $get_my_ride->vehicles->ryde->brand->name;
                    $array[$i]['model'] = $get_my_ride->vehicles->ryde->name;
                    $array[$i]['image'] = $get_my_ride->vehicles->ryde->image;
                    $array[$i]['year'] = $get_my_ride->vehicles->ryde->modelYear->name;
                    $array[$i]['color'] = $get_my_ride->vehicles->ryde->color->name;
                    $i++;
                }

                return response()->json([
                    'success'    => true,
                    'data'       => $array,
                    'total_page' => ceil($get_my_rides->total() / 25),
                ], 200);
            } else{
                return response()->json([
                    'success' => false,
                    'message' => Config('languageString.my_ryde_not_available'),
                ], 200);
            }
        } else{
            return response()->json([
                'success' => false,
                'message' => Config('languageString.token_expire'),
            ], 200);
        }

    }

    public function getRideDetails(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user_id = $user->id;

        $validator = Validator::make($request->all(), [
            'booking_id' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 403);
        }

        $get_booking = Booking::with('company', 'user')->where('id', $request->input('booking_id'))->first();
        if($get_booking){
            $array['status'] = $get_booking->status;
            $array['start_date'] = $get_booking->start_date;
            $array['end_date'] = $get_booking->end_date;
            $array['transaction_id'] = $get_booking->transaction_id;
            $array['total_day_rent'] = $get_booking->total_day_rent;
            $array['sub_total'] = $get_booking->sub_total;
            $array['pick_up_location'] = $get_booking->pick_up_location;
            $array['pick_up_latitude'] = $get_booking->pick_up_latitude;
            $array['pick_up_longitude'] = $get_booking->pick_up_longitude;
            $array['company_name'] = $get_booking->company->name;
            $array['user_name'] = $get_booking->user->name;
            $array['user_email'] = $get_booking->user->email;
            $array['user_country_code'] = $get_booking->user->country_code;
            $array['user_mobile_no'] = $get_booking->user->mobile_no;
            $array['tax_percentage'] = $get_booking->tax_percentage;
            $array['total_tax'] = $get_booking->total_tax;
            $array['tax_name'] = '';

            $company_details = CompanyAddress::with('country')->where('id', $get_booking->company_address_id)->first();
            if($company_details){
                $array['tax_name'] = $company_details->country->tax_name;
            }

            // DB::enableQueryLog();

            $extras = BookingDetails::with([
                'vehicleExtra' => function($query){
                    $query->with('extra');
                },
            ])->where('booking_id', $request->input('booking_id'))->get();
            //  dd(DB::getQueryLog());
            $extra_array = [];
            foreach($extras as $key => $extra){
                $extra_array[$key]['id'] = $extra->vehicleExtra->extra->id;
                $extra_array[$key]['name'] = $extra->vehicleExtra->extra->name;
                $extra_array[$key]['description'] = $extra->vehicleExtra->extra->description;
                $extra_array[$key]['type'] = $extra->vehicleExtra->type;
                $extra_array[$key]['price'] = $extra->vehicleExtra->price;
            }
            $array['extra'] = $extra_array;

            $inspections_query = BookingInspection::where('booking_id', $request->input('booking_id'))->get();
            $inspections = [];
            foreach($inspections_query as $key => $inspections_value){
                $inspections[$key]['inspection_type'] = $inspections_value->inspection_type;
                $inspections[$key]['front_image'] = $inspections_value->front_image;
                $inspections[$key]['back_image'] = $inspections_value->back_image;
                $inspections[$key]['left_image'] = $inspections_value->left_image;
                $inspections[$key]['right_image'] = $inspections_value->right_image;
            }
            $array['inspections'] = $inspections;

            return response()->json([
                'success' => true,
                'data'    => $array,
            ], 200);
        } else{
            return response()->json([
                'success' => false,
                'message' => Config('languageString.my_ryde_details_not_available'),
            ], 200);
        }
    }

    public function rideCancel(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user_id = $user->id;

        $validator = Validator::make($request->all(), [
            'booking_id' => 'required',
            'message'    => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 403);
        }

        Booking::where('id', $request->input('booking_id'))
            ->update(['status' => 'Review', 'message' => $request->input('message')]);

        return response()->json([
            'success' => true,
            'message' => Config('languageString.ryde_our_team_review_your_request'),
        ], 200);
    }
}
