<?php

namespace App\Http\Middleware;

use App\LanguageString;
use App\EmailString;
use App\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Closure;
use Illuminate\Support\Facades\App;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->header('Accept-Language') != ''){
            App::setLocale($request->header('Accept-Language'));
        }

        if(Schema::hasTable('language_strings')){
            $response = LanguageString::listsTranslations('name')
                ->select('language_strings.name_key', 'language_string_translations.name')
                ->get();
            foreach($response as $setting){
                Config::set('languageString.' . $setting->name_key, $setting->name);
            }
        }

        if(Schema::hasTable('email_strings')){
            $response = EmailString::listsTranslations('name')
                ->select('email_strings.name_key', 'email_string_translations.name')
                ->get();
            foreach($response as $setting){
                Config::set('email_string.' . $setting->name_key, $setting->name);
            }
        }

        return $next($request);
    }
}
