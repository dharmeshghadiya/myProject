<?php

namespace App\Http\Controllers\Dealer;


use App\Models\BranchExtra;
use App\Models\GlobalExtra;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BranchExtraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($company_address_id)
    {
        $global_extras = GlobalExtra::all();
        $extras = BranchExtra::where('company_address_id', $company_address_id)->get();
        $branch_extras = [];
        foreach($extras as $extra){
            $branch_extras[$extra->global_extra_id] = $extra->type;
        }

        return view('dealer.branch.extra.index', [
            'global_extras'      => $global_extras,
            'branch_extras'      => $branch_extras,
            'company_address_id' => $company_address_id,
        ]);
    }

    public function store(Request $request)
    {
        $extra_ids = $request->input('extra_ids');
        $company_address_id = $request->input('company_address_id');
        $extra = $request->input('extra');

        foreach($extra_ids as $key => $extra_id){
            BranchExtra::where('global_extra_id', $extra_id)->where('company_address_id', $company_address_id)
                ->update(['type' => $extra[$key]]);
        }

        return response()->json(['success' => true, 'message' => trans('adminMessages.extra_updated')]);

    }

}
