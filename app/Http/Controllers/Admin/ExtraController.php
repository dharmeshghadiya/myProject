<?php

namespace App\Http\Controllers\Admin;


use App\Models\Extra;
use App\Models\ExtraTranslation;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ExtraController extends Controller
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
            $extras = Extra::listsTranslations('name')
                ->select('extras.id')
                ->get();
            // dd(DB::getQueryLog());
            return Datatables::of($extras)
                ->addColumn('action', function ($extras) {
                    $edit_button = '<a href="' . route('admin::extra.edit', [$extras->id]) . '" class="btn btn-sm btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $extras->id . '" class="delete-single btn btn-sm btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    return $edit_button . ' ' . $delete_button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.extra.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $languages = Language::all();
        return view('admin.extra.create', ['languages' => $languages]);
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

        if ($id == NULL) {
            $extra_order = Extra::max('id');
            $insert_id = Extra::create([
                'extra_order' => $extra_order + 1,
            ]);
            $languages = Language::all();
            foreach ($languages as $language) {
                ExtraTranslation::create([
                    'name' => $request->input($language->language_code . '_name'),
                    'extra_id' => $insert_id->id,
                    'locale' => $language->language_code,
                ]);
            }

            return response()->json(['success' => true, 'message' => trans('adminMessages.extra_inserted')]);
        } else {
            $languages = Language::all();
            foreach ($languages as $language) {
                ExtraTranslation::updateOrCreate([
                    'extra_id' => $id,
                    'locale' => $language->language_code,
                ],
                [
                    'extra_id' => $id,
                    'locale' => $language->language_code,
                    'name' => $request->input($language->language_code . '_name')
                ]);

            }
            return response()->json(['success' => true, 'message' => trans('adminMessages.extra_updated')]);
        }
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
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $extra = Extra::find($id);
        if ($extra) {
            $languages = Language::all();
            return view('admin.extra.edit', ['extra' => $extra, 'languages' => $languages]);
        } else {
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
