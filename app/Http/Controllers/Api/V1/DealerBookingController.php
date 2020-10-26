<?php

namespace App\Http\Controllers\Api\V1;

use App\Booking;
use App\BookingInspection;
use Carbon\Carbon;
use DB;
use App\Helpers\ImageUploadHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class DealerBookingController extends Controller
{
    public function dealerGetBooking(Request $request)
    {
        $rules = [
            'company_id' => 'required',
            'timezone'   => 'required',
            'type'       => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 403);
        }
        $server_current_time_zone = Carbon::now()->setTimezone($request->input('timezone'));

        $query = Booking::with([
            'vehicles' => function($query){
                $query->with([
                    'ryde' => function($query){
                        $query->with('brand', 'modelYear', 'color');
                    },
                ]);
            },
        ])->where('company_id', $request->input('company_id'));
        if($request->input('type') == 0){
            $query->whereDate('start_date', '>=', $server_current_time_zone->format('Y-m-d'));
            $query->where('is_pickup', 0);
            $query->where('is_show', 1);
        } else if($request->input('type') == 1){
            $query->whereDate('end_date', '>=', $server_current_time_zone->format('Y-m-d'));
            $query->where('is_pickup', 1);
            $query->where('is_show', 1);
        } else if($request->input('type') == 2){
            $query->where('status', 'Completed');
            $query->where('is_show', 1);
        } else if($request->input('type') == 3){
            $query->where('status', 'Cancelled');
            $query->where('is_show', 1);
        } else if($request->input('type') == 4){
            $query->where('is_show', 0);
        }
        if($request->input('branch_id') != null){
            $query->where('company_address_id', $request->input('branch_id'));
        }
        $bookings = $query->orderBy('start_date', 'ASC')
            ->paginate(25);
        if(count($bookings) > 0){
            $array = [];
            $i = 0;

            foreach($bookings as $booking){
                $array[$i]['booking_id'] = $booking->id;
                $array[$i]['vehicle_id'] = $booking->vehicle_id;
                $array[$i]['start_date'] = $booking->start_date;
                $array[$i]['end_date'] = $booking->end_date;
                $array[$i]['booking_date'] = Carbon::parse($booking->start_date)->format('d') . ' - ' . Carbon::parse($booking->end_date)->format('d M y');
                $array[$i]['amount'] = $booking->sub_total;
                $array[$i]['total_tax'] = $booking->total_tax;
                $array[$i]['tax_percentage'] = $booking->tax_percentage;
                $array[$i]['trim'] = $booking->vehicles->trim;
                $array[$i]['hourly_amount'] = $booking->vehicles->hourly_amount;
                $array[$i]['daily_amount'] = $booking->vehicles->daily_amount;
                $array[$i]['weekly_amount'] = $booking->vehicles->weekly_amount;
                $array[$i]['monthly_amount'] = $booking->vehicles->monthly_amount;
                $array[$i]['brand'] = $booking->vehicles->ryde->brand->name;
                $array[$i]['model'] = $booking->vehicles->ryde->name;
                $array[$i]['image'] = $booking->vehicles->ryde->image;
                $array[$i]['year'] = $booking->vehicles->ryde->modelYear->name;
                $array[$i]['color'] = $booking->vehicles->ryde->color->name;

                if($request->input('type') == 0){
                    if(Carbon::parse($booking->start_date)->format('Y-m-d H:i:s') > $server_current_time_zone->format('Y-m-d H:i:s')){
                        $array[$i]['status'] = Config('languageString.up_coming_status');
                    } else{
                        $booking_start_date = Carbon::createFromFormat('Y-m-d H:i:s', $booking->start_date, $request->input('timezone'));
                        $diff_in_hour = $booking_start_date->diffInHours($server_current_time_zone, false);

                        if($diff_in_hour < 3 && $booking->is_pickup == 0){
                            $array[$i]['status'] = Config('languageString.due_for_pickup_status');
                        } else{
                            $array[$i]['status'] = Config('languageString.missed_pickup_status');
                        }
                    }
                } else if($request->input('type') == 1){
                    if(Carbon::parse($booking->end_date)->format('Y-m-d  H:i:s') > $server_current_time_zone->format('Y-m-d  H:i:s') && $booking->is_pickup == 1 && $booking->is_return == 0){
                        $array[$i]['status'] = Config('languageString.active_status');
                    } else{
                        $booking_start_date = Carbon::createFromFormat('Y-m-d H:i:s', $booking->end_date, $request->input('timezone'));
                        $diff_in_hour = $booking_start_date->diffInHours($server_current_time_zone, false);

                        if($diff_in_hour < 3 && $booking->is_pickup == 1 && $booking->is_return == 0){
                            $array[$i]['status'] = Config('languageString.missed_return_status');
                        } else{
                            $array[$i]['status'] = Config('languageString.due_for_return_status');
                        }
                    }
                } else if($request->input('type') == 2){
                    $array[$i]['status'] = Config('languageString.complete_status');
                } else if($request->input('type') == 3){
                    $array[$i]['status'] = Config('languageString.cancelled_status');
                } else if($request->input('type') == 4){
                    $array[$i]['status'] = Config('languageString.no_show_status');
                }
                $i++;
            }

            return response()->json([
                'success'    => true,
                'data'       => $array,
                'total_page' => ceil($bookings->total() / 25),
            ], 200);
        } else{
            return response()->json([
                'success' => false,
                'message' => Config('languageString.my_ryde_not_available'),
            ], 200);
        }

    }

    public function addInspection(Request $request)
    {
        $rules = [
            'booking_id'      => 'required',
            'inspection_type' => 'required',
            'front_image'     => 'image|mimes:jpeg,png,jpg',
            'back_image'      => 'image|mimes:jpeg,png,jpg',
            'left_image'      => 'image|mimes:jpeg,png,jpg',
            'right_image'     => 'image|mimes:jpeg,png,jpg',
        ];
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 403);
        }

        $is_inspection_add = BookingInspection::where('booking_id', $request->booking_id)
            ->where('inspection_type', $request->inspection_type)
            ->count();
        if($is_inspection_add == 0){

            $front_image = ImageUploadHelper::imageUpload($request->file('front_image'));
            $back_image = ImageUploadHelper::imageUpload($request->file('back_image'));
            $left_image = ImageUploadHelper::imageUpload($request->file('right_image'));
            $right_image = ImageUploadHelper::imageUpload($request->file('left_image'));

            $booking_inspection = new BookingInspection();
            $booking_inspection->booking_id = $request->booking_id;
            $booking_inspection->inspection_type = $request->inspection_type;
            $booking_inspection->front_image = $front_image;
            $booking_inspection->back_image = $back_image;
            $booking_inspection->left_image = $left_image;
            $booking_inspection->right_image = $right_image;
            $booking_inspection->save();

            return response()->json([
                'success' => true,
                'message' => Config('languageString.ryde_inspect_successfully'),
            ], 200);
        } else{
            return response()->json([
                'success' => false,
                'message' => Config('languageString.you_have_already_inspected_this_ryde'),
            ], 200);
        }
    }

}
