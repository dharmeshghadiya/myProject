<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use App\Models\ReportProblem;
use App\Models\User;
use Session;
use App\Helpers\ImageUploadHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{
    public function index()
    {
        return view('admin.admin.index');
    }

    public function changeThemes($id)
    {
        Session::put('panel_mode', $id);

        User::where('id', Auth::user()->id)->update(['panel_mode' => $id]);
        return redirect()->route('admin::admin');
    }

    public function changeThemesMode($local)
    {
        Session::put('locale', $local);

        User::where('id', Auth::user()->id)->update(['locale' => $local]);
        return redirect()->route('admin::admin');
    }

    public function getCount()
    {
        $report_problem_count = ReportProblem::where('is_seen', 0)->count();
        $contact_request_count = ContactUs::where('is_seen', 0)->count();

        return response()->json([
            'success'               => true,
            'report_problem_count'  => $report_problem_count,
            'contact_request_count' => $contact_request_count,
        ]);
    }

    public function viewLoad()
    {
        return view('emails.welcomeUser');
    }


    public function profile()
    {
        $user = User::where('id', Auth::user()->id)->first();
        if($user){
            return view('admin.admin.profile', ['user' => $user]);
        } else{
            abort(404);
        }
    }

    public function editProfile(Request $request)
    {
        $id = $request->input('edit_value');
        $user = User::where('id', $id)->first();
        $user->name = $request->input('name');
        if($request->hasFile('image')){
            $files = $request->file('image');
            $user->image = ImageUploadHelper::imageUpload($files);
        }
        $user->save();

        return response()->json(['success' => true, 'message' => trans('adminMessages.user_updated')]);
    }


}
