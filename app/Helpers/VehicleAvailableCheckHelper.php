<?php

namespace App\Helpers;

use App\Models\Booking;
use App\Models\VehicleNotAvailable;

class VehicleAvailableCheckHelper
{
    public static function inRideBetween($start_date, $end_date, $vehicle_id)
    {
        return Booking::where(function($query) use ($start_date, $end_date){
            $query->whereBetween('start_date', [$start_date, $end_date]);
            $query->OrWhereBetween('end_date', [$start_date, $end_date]);
        })->where('vehicle_id', $vehicle_id)
            ->where('status', '!=', 'Rejected')
            ->where('status', '!=', 'Review')
            ->where('status', '!=', 'Cancelled')
            ->where('status', '!=', 'Completed')->count();

    }
    public static function inRideAvailability($start_date, $end_date, $vehicle_id)
    {
        return VehicleNotAvailable::where(function($query) use ($start_date, $end_date){
            $query->whereBetween('start_date', [$start_date, $end_date]);
            $query->OrWhereBetween('end_date', [$start_date, $end_date]);
        })->where('vehicle_id', $vehicle_id)->count();

    }

}
