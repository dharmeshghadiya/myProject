<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\LanguageStringResource;
use DB;
use App\Models\Setting;
use App\Models\LanguageString;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LanguageStringController extends Controller
{
    public function index($locale)
    {
        $settings = Setting::all();

        $support_contact_no = $whatsapp= '';
        foreach($settings as $setting){
            if($setting->meta_key == 'support_contact_no'){
                $support_contact_no = $setting->meta_value;
            } else if($setting->meta_key == 'whatsApp\Models_no'){
                $whatsapp = $setting->meta_value;
            }
        }
        $Language_strings = LanguageString::where('app_or_panel', 1)
            ->get();
        $Language_string_screen = $language_string = [];
        foreach($Language_strings as $language_string){
            $Language_string_screen[$language_string->name_key] = $language_string->name;
        }

        if(count($Language_string_screen) > 0){
            $response = [
                'success'            => true,
                'data'               => $Language_string_screen,
                'support_contact_no' => $support_contact_no,
                'whatsapp'        => $whatsapp
            ];
        } else{
            $response = [
                'success'            => false,
                'message'            => Config('languageString.language_string_not_found'),
                'support_contact_no' => $support_contact_no,
                'whatsapp'        => $whatsapp,
            ];
        }

        return response()->json($response, 200);
    }
}
