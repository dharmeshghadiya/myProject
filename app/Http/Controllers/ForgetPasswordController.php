<?php

namespace App\Http\Controllers;

use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use App\Mail\ResetPasswordEmail;

class ForgetPasswordController extends Controller
{

    public function forgetPasswordForm()
    {
        return view('auth.passwords.forget');
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator->errors())
                ->withInput($request->input());
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



            return redirect()->back()
                ->with('success_message', Config('languageString.please_check_your_mail'));

        }else{

            return redirect()->back()->with(['error_message' => 'Email not found']);
        }

    }
}
