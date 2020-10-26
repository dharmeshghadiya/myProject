<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\User;
use Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function index()
    {
        return view('admin.auth.login');
    }

    public function loginCheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()
                ->route('admin/login')
                ->withErrors($validator)
                ->withInput();

        }


        $user = User::where('email', $request->input('email'))
            ->where(function ($query) {
                $query->where('user_type', 'admin');
                $query->orWhere('user_type', 'company');
            })
            ->where('status', 'Active')
            ->first();

        if (!$user) {
            return redirect()
                ->route('admin::login')
                ->withErrors(['email' => ['Invalid email']])
                ->withInput();
        }

        if (!Hash::check($request->input('password'), $user->password)) {
            return redirect()
                ->route('admin::login')
                ->withErrors(['password' => ['Invalid password']])
                ->withInput();
        } else {
            Auth::login($user);
            Session::put('panel_mode', $user->panel_mode);
            Session::put('locale', $user->locale);
            return redirect()->route('admin::admin');
        }

    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('admin/login');
    }
}
