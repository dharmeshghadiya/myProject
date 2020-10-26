<?php

namespace App\Http\Controllers\Admin;


use App\Announcement;
use App\AnnouncementTranslation;
use App\Country;
use App\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $announcements = Announcement::listsTranslations('name')
                ->with('countries')
                ->select('announcements.id', 'announcements.country_id')
                ->get();
            return Datatables::of($announcements)
                ->addColumn('country', function ($announcements) {
                    return $announcements->countries->name;
                })
                ->addColumn('action', function ($announcements) {
                    $edit_button = '<a href="' . route('admin::announcement.edit', [$announcements->id]) . '" class="btn btn-icon btn-info" data-toggle="tooltip" data-placement="top" title="Edit"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $announcements->id . '" class="delete-single btn btn-icon btn-danger" data-toggle="tooltip" data-placement="top" title="Delete"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    return '<div class="btn-icon-list">' .$edit_button . ' ' . $delete_button.'</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.announcement.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $languages = Language::all();
        $countries = Country::all();
        return view('admin.announcement.create', ['languages' => $languages, 'countries' => $countries]);
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
            $validator_array = [
                'country' => 'required',
            ];
            $duplicate = Announcement::where('country_id', $request->input('country'))->first();
        } else {
            $validator_array = [
                'country' => 'required',
            ];
            $duplicate = Announcement::where('country_id', $request->input('country'))
                ->where('id', '!=', $id)->first();
        }
        if ($duplicate) {
            return response()->json(['success' => false, 'message' => trans('adminMessages.announcement_duplicate')]);
        } else {
            if ($id == NULL) {
                $validator = Validator::make($request->all(), $validator_array);
                if ($validator->fails()) {
                    return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
                }
                $insert_id = Announcement::create([
                    'country_id' => $request->input('country'),
                ]);
                $languages = Language::all();
                foreach ($languages as $language) {
                    AnnouncementTranslation::create([
                        'name' => $request->input($language->language_code . '_name'),
                        'announcement_id' => $insert_id->id,
                        'locale' => $language->language_code,
                    ]);
                }
                return response()->json(['success' => true, 'message' => trans('adminMessages.announcement_inserted')]);
            } else {
                Announcement::where('id', $id)->update([
                    'country_id' => $request->input('country'),
                ]);

                $languages = Language::all();
                foreach ($languages as $language) {
                    AnnouncementTranslation::updateOrCreate([
                        'announcement_id' => $id,
                        'locale' => $language->language_code,
                    ],
                        [
                            'announcement_id' => $id,
                            'locale' => $language->language_code,
                            'name' => $request->input($language->language_code . '_name')
                        ]);

                }
                return response()->json(['success' => true, 'message' => trans('adminMessages.announcement_updated')]);
            }
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $announcement = Announcement::find($id);
        if ($announcement) {
            $languages = Language::all();
            $countries = Country::all();
            return view('admin.announcement.edit', ['announcement' => $announcement, 'languages' => $languages, 'countries' => $countries]);
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
        Announcement::where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => trans('adminMessages.announcement_deleted')]);
    }
}
