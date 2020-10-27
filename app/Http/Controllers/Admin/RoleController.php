<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ability;
use App\Models\Role;
use App\Models\RoleAbility;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            //DB::enableQueryLog();
            $roles = Role::get();
            // dd(DB::getQueryLog());
            return Datatables::of($roles)
                ->addColumn('action', function ($roles) {
                    $edit_button = '<a href="' . route('admin::roles.edit', [$roles->id]) . '" class="btn btn-sm btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $roles->id . '" class="delete-single btn btn-sm btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    return $edit_button . ' ' . $delete_button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.role.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $abilities = Ability::all();
        return view('admin.role.create', ['abilities' => $abilities]);
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

        $validator_array = [
            'name' => 'required|unique:abilities|max:191',
            'ability' => 'required',
        ];
        $validator = Validator::make($request->all(), $validator_array);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        if ($id == NULL) {
            $role = Role::create([
                'name' => $request->input('name'),
            ]);
            foreach ($request->input('ability') as $value) {
                RoleAbility::create([
                    'role_id' => $role->id,
                    'ability_id' => $value,
                ]);
            }
            return response()->json(['success' => true, 'message' => trans('adminMessages.role_inserted')]);
        } else {
            Role::where('id', $id)->update([
                'name' => $request->input('name'),
            ]);

            RoleAbility::where('role_id', $id)->delete();
            foreach ($request->input('ability') as $key => $value) {
                $ability_value = $request->input('is_ability')[$key];
                if ($ability_value == 1) {
                    RoleAbility::create([
                        'role_id' => $id,
                        'ability_id' => $value,
                    ]);
                }
            }
            return response()->json(['success' => true, 'message' => trans('adminMessages.role_updated')]);
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

        $role = Role::find($id);
        $ability_ids = $role->roleAbility()->pluck('ability_id')->toArray();
        //dd($ability_ids);
        if ($role) {
            $abilities = Ability::all();
            return view('admin.role.edit', [
                'role' => $role,
                'abilities' => $abilities,
                'ability_ids' => $ability_ids
            ]);
        } else {
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
        Role::where('id', $id)->delete();
        RoleAbility::where('role_id', $id)->delete();


        return response()->json(['success' => true, 'message' => trans('adminMessages.role_deleted')]);
    }
}
