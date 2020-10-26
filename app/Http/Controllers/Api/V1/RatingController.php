<?php

namespace App\Http\Controllers\Api\V1;


use Rating;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class RatingController extends Controller
{
    public function addRating(Request $request)
    {
        $rules = [
            'booking_id'         => 'required',
            'price'              => 'required',
            'service'            => 'required',
            'condition'          => 'required',
            'pickup_up_drop_off' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 403);
        }

        if(Rating::where('booking_id', $request->input('booking_id'))->count() == 0){
            $rating = new Rating();
            $rating->booking_id = $request->input('booking_id');
            $rating->price = $request->input('price');
            $rating->service = $request->input('service');
            $rating->condition = $request->input('condition');
            $rating->pickup_up_drop_off = $request->input('pickup_up_drop_off');
            $rating->save();
            return response()->json([
                'success' => true,
                'message' => config('languageString.thanks_for_rating'),
            ], 200);
        } else{
            return response()->json([
                'success' => false,
                'message' => config('languageString.you_have_already_rating_this_booking'),
            ], 403);
        }

    }

}
