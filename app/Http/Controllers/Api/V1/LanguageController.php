<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\LangaugeResource;
use App\Language;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class LanguageController extends Controller
{
    public function index()
    {
        $user_id = 0;
        $is_notification_on = 1;
        if(JWTAuth::getToken() != NULL){
            $user = JWTAuth::parseToken()->authenticate();
            $user_id = $user->id;
            $user_details = User::where('id', $user_id)->select('is_notification_on')->first();
            $is_notification_on = $user_details->is_notification_on;
        }

        $languages = LangaugeResource::collection(Language::where('status', 'Active')->get());

        if(count($languages) > 0){
            return [
                'success'            => true,
                'data'               => $languages,
                'is_notification_on' => $is_notification_on,
            ];
        } else{
            return [
                'success' => false,
                'message' =>  Config('languageString.language_not_found'),
            ];
        }
    }
}
