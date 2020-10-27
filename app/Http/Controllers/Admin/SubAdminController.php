<?php

namespace App\Http\Controllers\Admin;


use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SubAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if($request->ajax()){
            $users = User::where('user_type', 'admin')
                ->where(function($query){
                    $query->where('id', '!=', 1);
                    $query->orWhere('id', '!=', Auth::id());
                })->get();

            // dd(DB::getQueryLog());
            return Datatables::of($users)
                ->addColumn('action', function($users){
                    $edit_button = '<a href="' . route('admin::admins.edit', [$users->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $users->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '""><i class="bx bx-trash font-size-16 align-middle"></i></button>';

                    return '<div class="btn-icon-list">' . $edit_button . ' ' . $delete_button . '</div>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('admin.subAdmin.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.subAdmin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $id = $request->input('edit_value');

        if($id == NULL){
            $validator_array = [
                'name'     => 'required|max:191',
                'email'    => 'required|unique:users|max:191',
                'password' => 'required|min:9|max:20',
            ];
            $validator = Validator::make($request->all(), $validator_array);
            if($validator->fails()){
                return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
            }

            User::create([
                'name'       => $request->input('name'),
                'email'      => $request->input('email'),
                'password'   => bcrypt($request->input('password')),
                'panel_mode' => 2,
                'user_type'  => 'admin',
                'locale'     => 'en',
            ]);

            return response()->json(['success' => true, 'message' => trans('adminMessages.admin_user_inserted')]);
        } else{
            if($request->input('is_password_update') == NULL){
                $validator_array = [
                    'name'  => 'required|max:191',
                    'email' => 'required|unique:users,id,' . $id,
                ];
            } else{
                $validator_array = [
                    'name'     => 'required|max:191',
                    'email'    => 'required|unique:users,id,' .$id,
                    'password' => 'required|min:9|max:20',
                ];
            }
            $validator = Validator::make($request->all(), $validator_array);
            if($validator->fails()){
                return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
            }


            User::where('id', $id)->update([
                'name'  => $request->input('name'),
                'email' => $request->input('email'),
            ]);
            if($request->input('is_password_update') == 1){
                User::where('id', $id)->update([
                    'password' => bcrypt($request->input('password')),
                ]);
            }
            return response()->json(['success' => true, 'message' => trans('adminMessages.admin_user_updated')]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::find($id);
        if($user){
            return view('admin.subAdmin.edit', ['user' => $user]);
        } else{
            abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        User::where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => trans('adminMessages.admin_user_deleted')]);
    }
}
