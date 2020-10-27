<?php

namespace App\Http\Controllers\Admin;


use App\Models\Country;
use App\Models\DriverRequirement;
use App\Models\DriverRequirementTranslation;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DriverRequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id)
    {
        $country = Country::where('id', $id)->first();
        if($country){
            $driver_requirements = DriverRequirement::where('country_id', $id)->get();
            return view('admin.driverRequirement.index', [
                'country'             => $country,
                'driver_requirements' => $driver_requirements,
            ]);
        } else{
            abort(404);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $country = Country::where('id', $id)->first();
        if($country){
            $languages = Language::all();
            return view('admin.driverRequirement.create', ['country' => $country, 'languages' => $languages]);
        } else{
            abort(404);
        }
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
            $insert_id = DriverRequirement::create([
                'country_id' => $request->input('country_id'),
            ]);
            $languages = Language::all();
            foreach($languages as $language){
                DriverRequirementTranslation::create([
                    'driver_requirement_id' => $insert_id->id,
                    'name'                  => $request->input($language->language_code . '_name'),
                    'locale'                => $language->language_code,
                ]);
            }
            return response()->json(['success' => true, 'message' => trans('adminMessages.driver_requirement_inserted')]);
        } else{

            $languages = Language::all();
            foreach($languages as $language){
                DriverRequirementTranslation::updateOrCreate([
                    'driver_requirement_id' => $id,
                    'locale'                => $language->language_code,
                ],
                    [
                        'driver_requirement_id' => $id,
                        'locale'                => $language->language_code,
                        'name'                  => $request->input($language->language_code . '_name'),
                    ]);

            }
            return response()->json(['success' => true, 'message' => trans('adminMessages.driver_requirement_updated')]);
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
    public function edit($id, $country_id)
    {
        $driver_requirement = DriverRequirement::find($id);
        if($driver_requirement){
            $languages = Language::all();
            $country = Country::find($country_id);
            return view('admin.driverRequirement.edit', [
                'driver_requirement' => $driver_requirement,
                'languages'          => $languages,
                'country'            => $country,
            ]);
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
        DriverRequirement::where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => trans('adminMessages.driver_requirement_deleted')]);
    }
}
