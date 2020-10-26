<?php

use App\Language;
use Illuminate\Database\Seeder;
use App\Fuel;
use App\FuelTranslation;

class FuelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            ['en' => 'Gasoline', 'ar' => 'الغازولين'],
            ['en' => 'Gasoline, Electric', 'ar' => 'بنزين ، كهربائي'],
            ['en' => 'Gasoline, Gas', 'ar' => 'بنزين وغاز'],
            ['en' => 'Diesel', 'ar' => 'ديزل'],
            ['en' => 'Diesel, Hybrid', 'ar' => 'ديزل ، هجين'],
            ['en' => 'Electric', 'ar' => 'كهربائي'],
            ['en' => 'Gas', 'ar' => 'غاز'],
            ['en' => 'Hybrid', 'ar' => 'هجين'],
        ];
        $languages = Language::all();

        for($i = 1; $i <= 7; $i++){
            $fuel = new Fuel();
            $fuel->fuel_order = $i + 1;
            $fuel->save();

            foreach($languages as $language){
                $fuel_translations = new FuelTranslation();
                $fuel_translations->fuel_id = $fuel->id;
                $fuel_translations->name = $array[$i][$language->language_code];
                $fuel_translations->locale = $language->language_code;
                $fuel_translations->save();
            }
        }
    }
}
