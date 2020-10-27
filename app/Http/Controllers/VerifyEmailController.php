<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeUserMail;
use App\Models\User;
use App\Models\EmailString;
use DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;


class VerifyEmailController extends Controller
{

    public function __construct()
    {
        $response = EmailString::listsTranslations('name')
            ->select('email_strings.name_key', 'email_string_translations.name')
            ->get();
        foreach($response as $setting){
            Config::set('email_string.' . $setting->name_key, $setting->name);
        }
    }


    public function verifyEmail($token)
    {

        $is_token = User::where('token', $token)->where('is_verify', 0)->first();
        if($is_token){
            $affected = User::where('id', $is_token->id)->update(
                [
                    'is_verify' => 1,
                ]
            );
            if($affected == 1){
                $array = [
                    'name'                       => $is_token->name,
                    'welcome_email_title'        => Config('email_string.welcome_email_title'),
                    'user_welcome_email_subject' => Config('email_string.user_welcome_email_subject'),
                ];
                Mail::to($is_token->email)->send(new WelcomeUserMail($array));
            }
            return view('auth.verify');
        } else{
            return view('auth.expireLink');
        }
    }
}
