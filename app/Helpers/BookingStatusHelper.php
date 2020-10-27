<?php

namespace App\Helpers;

use Carbon\Carbon;



class BookingStatusHelper
{
    public static function getBookingStatus($array)
    {
        $array['timezone'] = 'Asia/Kuwait';
        $branch_time_zone = Carbon::now()->setTimezone($array['timezone']);
        $branch_date_time = $branch_time_zone->format('Y-m-d H:i:s');
        $booking_start_date = Carbon::createFromFormat('Y-m-d H:i:s', $array['start_date'], $array['timezone']);
        $star_diff_in_hour = $booking_start_date->diffInHours($branch_time_zone, false);

        $booking_end_date = Carbon::createFromFormat('Y-m-d H:i:s', $array['end_date'], $array['timezone']);
        $end_diff_in_hour = $booking_end_date->diffInHours($branch_time_zone, false);

        if($array['status'] == 'Booked'){
            if(Carbon::parse($array['start_date'])->format('Y-m-d H:i:s') > $branch_date_time && $array['is_pickup'] == 0 && $array['is_return'] == 0){
                return [Config('languageString.up_coming_status'), 1];
            } else if($star_diff_in_hour < 3 && $array['is_pickup'] == 0){
                return [Config('languageString.due_for_pickup_status'), 2];
            } else if(Carbon::parse($array['end_date'])->format('Y-m-d H:i:s') > $branch_date_time && $array['is_pickup'] == 1 && $array['is_return'] == 0){
                return [Config('languageString.active_status'), 3];
            } else if($end_diff_in_hour < 3 && $array['is_pickup'] == 1 && $array['is_return'] == 0){
                return [Config('languageString.missed_return_status'), 4];
            } else if($array['is_pickup'] == 0 && $array['is_return'] == 0 && $star_diff_in_hour < 3 && $end_diff_in_hour < 3){
                return [Config('languageString.due_for_return_status'), 5];
            } else{
                return [Config('languageString.no_show_status'), 6];
            }
        } else if($array['status'] == 'Review'){
            return [Config('languageString.review_status'), 7];
        } else if($array['status'] == 'Cancelled'){
            return [Config('languageString.cancelled_status'), 0];
        } else{
            return [Config('languageString.complete_status'), 0];
        }
    }

}
