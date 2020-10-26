<?php

namespace App\Http\Controllers\Dealer;


use App\DealerExtra;
use App\Extra;
use App\ExtraTranslation;
use App\GlobalExtra;
use App\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ExtraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $global_extras = GlobalExtra::all();
        $extras = DealerExtra::where('user_id', Auth::id())->get();
        $dealer_extras = [];
        foreach($extras as $extra){
            $dealer_extras[$extra->global_extra_id] = $extra->type;
        }
        return view('dealer.extra.index', [
            'global_extras' => $global_extras,
            'dealer_extras' => $dealer_extras,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $languages = Language::all();
        return view('dealer.extra.create', ['languages' => $languages]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $extra_ids = $request->input('extra_ids');
        $extra = $request->input('extra');

        foreach($extra_ids as $key => $extra_id){
            DealerExtra::where('global_extra_id', $extra_id)->where('user_id', Auth::id())
                ->update(['type' => $extra[$key]]);
        }

        return response()->json(['success' => true, 'message' => trans('adminMessages.extra_updated')]);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $extra = Extra::find($id);
        if($extra){
            $languages = Language::all();
            return view('dealer.extra.edit', ['extra' => $extra, 'languages' => $languages]);
        } else{
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Extra::where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => trans('adminMessages.extra_deleted')]);
    }
}
