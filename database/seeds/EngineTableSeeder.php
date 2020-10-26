<?php

use Illuminate\Database\Seeder;
use App\Engine;
use App\EngineTranslation;
use App\Language;

class EngineTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            ['en' => '3 Cylinder', 'ar' => '3 Cylinder'],
            ['en' => '4 Cylinder', 'ar' => '4 Cylinder'],
            ['en' => '5 Cylinder', 'ar' => '5 Cylinder'],
            ['en' => '6 Cylinder', 'ar' => '6 Cylinder'],
            ['en' => '7 Cylinder', 'ar' => '7 Cylinder'],
            ['en' => '8 Cylinder', 'ar' => '8 Cylinder'],
            ['en' => '10 Cylinder', 'ar' => '10 Cylinder'],
            ['en' => '12 Cylinder', 'ar' => '12 Cylinder'],
            ['en' => 'Rotary Engine', 'ar' => 'Rotary Engine'],
        ];
        $languages = Language::all();

        for($i = 1; $i <= 8; $i++){
            $engine = new Engine();
            $engine->vehicle_type_id = 1;
            $engine->engine_order = $i + 1;
            $engine->save();

            foreach($languages as $language){
                $engine_translations = new EngineTranslation();
                $engine_translations->engine_id = $engine->id;
                $engine_translations->name = $array[$i][$language->language_code];
                $engine_translations->locale = $language->language_code;
                $engine_translations->save();
            }
        }
    }
}
