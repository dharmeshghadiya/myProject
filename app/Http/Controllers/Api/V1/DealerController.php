<?php

namespace App\Http\Controllers\Api\V1;

use App\User;
use App\Device;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use League\Flysystem\Config;
use Illuminate\Support\Facades\URL;
use Tymon\JWTAuth\Facades\JWTAuth;


class DealerController extends Controller
{

    public function dealerGetProfile(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user_id = $user->id;

        $user = User::with('companies')
            ->where('id', $user_id)
            ->first();
        if($user){
            return response()->json([
                'success' => true,
                'data'    => $user,
            ], 200);
        } else{
            return response()->json([
                'success' => false,
                'message' => Config('languageString.no_user_found'),
            ], 200);
        }
    }

    public function updateProfile(Request $request)
    {
        $rules = [
            'name'         => 'required',
            'country_code' => 'required|max:10',
            'mobile_no'    => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 403);
        }

        $user = JWTAuth::parseToken()->authenticate();
        $user_id = $user->id;

        User::where('id', $user_id)->update([
            'name'         => $request->input('name'),
            'country_code' => $request->input('country_code'),
            'mobile_no'    => $request->input('mobile_no'),
        ]);

        return response()->json([
            'success' => true,
            'message' => Config('languageString.profile_updated'),
        ], 200);

    }

}
