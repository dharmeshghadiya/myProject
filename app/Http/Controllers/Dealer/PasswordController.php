<?php

namespace App\Http\Controllers\Dealer;


use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function index()
    {
        $user = User::where('id', Auth::user()->id)->first();
        if($user){
            return view('admin.password.changePassword', ['user' => $user]);
        } else{
            abort(404);
        }
    }


    public function changePassword(Request $request){
            $id = Auth::user()->id;
            $user = User::where('id',$id)->first();
            $current_password = $request->input('current_password');
            $new_password = $request->input('new_password');
            $confirmed_password = $request->input('confirmed_password');

            if($new_password !=$confirmed_password){
            return response()->json(['success' => false, 'message' => trans('adminMessages.password_does_not_match')]);
            }
            elseif (Hash::check($request->input('current_password'), $user->password)) {

                 $update = User::where('id', $id)->update([
                    'password'      => bcrypt($request->input('new_password')),
                ]);
                if($update){
                    return response()->json(['success' => true, 'message' => trans('adminMessages.password_changed')]);
                }else{
                    return response()->json(['success' => false, 'message' => trans('adminMessages.password_not_changed')]);
                }



            }else{
             return response()->json(['success' => false, 'message' => trans('adminMessages.curent_password_is_invalid')]);
            }
        }


}
