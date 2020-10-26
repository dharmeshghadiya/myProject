<?php

use App\Feature;
use App\FeatureTranslation;
use App\Language;
use Illuminate\Database\Seeder;

class FeatureTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            ['en' => 'GPS', 'ar' => 'GPS'],
            ['en' => 'Full Insurance', 'ar' => 'Full Insurance'],
            ['en' => 'Airport Service', 'ar' => 'Airport Service'],
            ['en' => 'Unlimited Mileage', 'ar' => 'Unlimited Mileage'],
            ['en' => 'Full Fuel', 'ar' => 'Full Fuel'],
        ];
        $languages = Language::all();

        for($i = 1; $i <= 4; $i++){
            $feature = new Feature();
            $feature->vehicle_type_id = 1;
            $feature->feature_order = $i + 1;
            $feature->image = 'uploads/default/feature/' . $i . '.png';
            $feature->save();

            foreach($languages as $language){
                $feature_translations = new FeatureTranslation();
                $feature_translations->feature_id = $feature->id;
                $feature_translations->name = $array[$i][$language->language_code];
                $feature_translations->locale = $language->language_code;
                $feature_translations->save();
            }
        }
    }
}
