<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Company;
use App\Models\Device;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use League\Flysystem\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use App\Mail\ResetPasswordEmail;
use App\Mail\VerificationEmail;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\API\AppleLoginRequest;
use App\Http\Requests\API\FacebookLoginRequest;
use App\Http\Requests\API\GoogleLoginRequest;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $rules = [
            'email'    => 'required|email',
            'password' => 'required|min:8|max:20',
        ];
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 403);
        }

        $credentials = $request->only('email', 'password');

        try{
            if(!$token = JWTAuth::attempt($credentials)){
                return response()->json([
                    'success' => false,
                    'message' => Config('languageString.invalid_credentials'),
                ], 400);
            }
        } catch(JWTException $e){
            return response()->json([
                'success' => false,
                'message' => Config('languageString.could_not_create_token'),
            ], 500);
        }
        $user = User::where(['email' => $request->input('email')])->first();
        if($user->is_verify == 0){
            $array = [
                'name'                       => $user->name,
                'actionUrl'                  => route('verify-email', [$user->token]),
                'main_title_text'            => Config('email_string.title_verification_email_reset'),
                'verification_email_subject' => Config('email_string.verification_email_subject'),
            ];
            Mail::to($request->input('email'))->send(new VerificationEmail($array));
            return response()->json([
                'success' => false,
                'message' => Config('languageString.please_check_your_mail_and_verify_your_email_address'),
            ], 200);
        } else{
            if($request->input('device_type') != NULL && $request->input('device_token') != NULL){
                $device = new Device();
                $device->user_id = $user->id;
                $device->device_type = $request->input('device_type');
                $device->device_token = $request->input('device_token');
                $device->save();
            }
            $company_id = null;
            $company_query = Company::where('user_id', $user->id)->select('id')->first();
            if($company_query){
                $company_id = $company_query->id;
            }
            return response()->json([
                'success'      => true,
                'name'         => $user->name,
                'email'        => $user->email,
                'country_code' => $user->country_code,
                'mobile_no'    => $user->mobile_no,
                'user_type'    => $user->user_type,
                'token'        => $token,
                'company_id'   => $company_id,
            ], 200);
        }
    }

    public function signUp(Request $request)
    {
        $rules = [
            'name'         => 'required',
            'email'        => 'required|email|unique:users',
            'password'     => 'required|min:8|max:20',
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

        $verify_token = Str::random(32);
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->country_code = $request->input('country_code');
        $user->mobile_no = $request->input('mobile_no');
        $user->user_type = 'user';
        $user->password = Hash::make($request->input('password'));
        $user->token = $verify_token;
        $user->is_verify = 0;
        $user->save();


        // $token = JWTAuth::fromUser($user);

        $array = [
            'name'      => $request->input('name'),
            'actionUrl' => route('verify-email', [$verify_token]),
        ];
        Mail::to($request->input('email'))->send(new VerificationEmail($array));

        return response()->json([
            'success' => true,
            'message' => Config('languageString.please_check_your_mail_and_verify_your_email_address'),
        ], 200);

        /*return response()->json([
            'success'      => true,
            'name'         => $user->name,
            'email'        => $user->email,
            'country_code' => $user->country_code,
            'mobile_no'    => $user->mobile_no,
            'token'        => $token,
        ], 200);*/
    }

    public function getProfile(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user_id = $user->id;

        $user = User::where('id', $user_id)->first();
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

    public function forgotPassword(Request $request)
    {
        $rules = [
            'email' => 'required|email',
        ];
        $validator = Validator::make($request->all(), $rules);


        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 403);
        }

        $user = User::where(['email' => $request->input('email')])->first();

        if($user){

            $token = Password::getRepository()->create($user);

            $array = [
                'name'                   => $user->name,
                'actionUrl'              => route('reset-password', [$token]),
                'mail_title'             => Config('email_string.title_password_reset'),
                'reset_password_subject' => Config('email_string.reset_password_subject'),
                'main_title_text'        => Config('email_string.title_password_reset'),
            ];
            Mail::to($request->input('email'))->send(new ResetPasswordEmail($array));

            //$user->sendPasswordResetNotification($token);

            return response()->json([
                'success' => true,
                'message' => Config('languageString.please_check_your_mail'),
            ], 200);

        }

    }

    public function resetPassword(Request $request)
    {
        $rules = [
            'token'        => 'required',
            'new_password' => 'required|min:8|max:20',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 403);
        }

        $new_password = $request->input('new_password');
        $tokens = DB::table('password_resets')->select('email', 'token')->get();

        if(count($tokens) > 0){
            foreach($tokens as $token){
                if(Hash::check($request->input('token'), $token->token)){
                    $user = User::where('email', $token->email)->first();
                    if($user){
                        $user->password = bcrypt($new_password);
                        $user->update();

                        DB::table('password_resets')->where('email', $user->email)
                            ->delete();
                        return response()->json([
                            'success' => true,
                            'message' => Config('languageString.password_reset_successfully'),
                        ], 200);

                    } else{
                        return response()->json([
                            'success' => false,
                            'message' => Config('languageString.user_not_fund'),
                        ], 200);
                    }
                }
            }
        }

        return response()->json([
            'success' => false,
            'message' => Config('languageString.this_link_is_expire'),
        ], 200);

    }

    public function updateNotificationStatus(Request $request)
    {
        $rules = [
            'is_notification_on' => 'required',
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
            'is_notification_on' => $request->input('is_notification_on'),
        ]);

        return response()->json([
            'success' => true,
            'message' => Config('languageString.notification_status_updated'),
        ], 200);

    }

    public function refreshToken()
    {
        $token = JWTAuth::getToken();
        $new_token = JWTAuth::refresh($token);
        return response()->json(['success' => true, 'token' => $new_token], 200);
    }

    public function facebookLogin(FacebookLoginRequest $request)
    {
        if($request->email != NULL){
            $user = User::where('email', $request->email)->first();
            if($user){
                if($user->facebook_id == null){
                    return response()->json([
                        'success' => false,
                        'message' => Config('languageString.this_email_is_already_used_another_method_of_login'),
                    ], 422);
                }
            }
        }

        $user = User::where('facebook_id', $request->facebook_id)->first();
        if($user){
            User::where('id', $user->id)
                ->update([
                    'name'  => $request->name,
                    'email' => $request->email,
                ]);
        } else{
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->user_type = 'user';
            $user->facebook_id = $request->facebook_id;
            $user->is_verify = 1;
            $user->status = 1;
            $user->save();
        }


        $token = JWTAuth::fromUser($user);

        $this->deleteToken($request->device_token);

        $device = new Device();
        $device->user_id = $user->id;
        $device->device_type = $request->device_type;
        $device->device_token = $request->device_token;
        $device->save();

        return response()->json([
            'success' => true,
            'token'   => $token,
            'data'    => $user,
        ], 200);
    }

    public function googleLogin(GoogleLoginRequest $request)
    {

        $user = User::where('email', $request->email)->first();
        if($user){
            if($user->google_id == null){
                User::where('id', $user->id)
                    ->update([
                        'name'      => $request->name,
                        'google_id' => $request->google_id,
                    ]);
            } else if($user->google_id != $request->google_id){
                return response()->json([
                    'success' => false,
                    'message' => Config('languageString.this_email_is_already_used_another_method_of_login'),
                ], 422);
            } else{
                $user = User::where('google_id', $request->google_id)->first();
                if($user){
                    User::where('id', $user->id)
                        ->update([
                            'name' => $request->name,
                        ]);
                } else{
                    $user = new User();
                    $user->name = $request->name;
                    $user->email = $request->email;
                    $user->user_type = 'user';
                    $user->google_id = $request->google_id;
                    $user->is_verify = 1;
                    $user->status = 1;
                    $user->save();
                }
            }
        }


        $token = JWTAuth::fromUser($user);
        $this->deleteToken($request->device_token);

        $device = new Device();
        $device->user_id = $user->id;
        $device->device_type = $request->device_type;
        $device->device_token = $request->device_token;
        $device->save();

        return response()->json([
            'success' => true,
            'token'   => $token,
            'data'    => $user,
        ], 200);
    }

    public function AppleLogin(AppleLoginRequest $request)
    {
        if($request->email != NULL){
            $user = User::where('email', $request->email)->first();
            if($user){
                if($user->apple_id == null){
                    return response()->json([
                        'success' => false,
                        'message' => Config::get('languageString.this_email_is_already_used_another_method_of_login'),
                    ], 422);
                }
            }
        }


        $user = User::where('apple_id', $request->apple_id)->first();
        if($user){
            User::where('id', $user->id)
                ->update([
                    'name'  => $request->name,
                    'email' => $request->email,
                ]);
        } else{
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->user_type = 'user';
            $user->apple_id = $request->apple_id;
            $user->is_verify = 1;
            $user->status = 1;
            $user->save();
        }


        $token = JWTAuth::fromUser($user);

        $this->deleteToken($request->device_token);

        $device = new Device();
        $device->user_id = $user->id;
        $device->device_type = $request->device_type;
        $device->device_token = $request->device_token;
        $device->save();

        return response()->json([
            'success' => true,
            'token'   => $token,
            'data'    => $user,
        ], 200);
    }

    public function deleteToken($device_token)
    {
        Device::where('device_token', $device_token)->delete();
        return true;
    }

}
