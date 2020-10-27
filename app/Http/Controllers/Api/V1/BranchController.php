<?php

namespace App\Http\Controllers\Api\V1;


use App\Models\Company;
use App\Models\CompanyAddress;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BranchResource;
use Tymon\JWTAuth\Facades\JWTAuth;


class BranchController extends Controller
{

    public function getBranch(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user_id = $user->id;

        $user = CompanyAddress::where('user_id', $user_id)
            ->get();
        if(count($user) > 0){
            $company_details = Company::where('user_id', $user_id)->select('id')->first();
            return response()->json([
                'success'    => true,
                'data'       => BranchResource::collection($user),
                'company_id' => $company_details->id,
            ], 200);
        } else{
            return response()->json([
                'success' => false,
                'message' => Config('languageString.no_user_found'),
            ], 200);
        }
    }

}
