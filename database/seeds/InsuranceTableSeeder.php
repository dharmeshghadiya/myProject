<?php

use App\Language;
use App\Insurance;
use App\InsuranceTranslation;
use Illuminate\Database\Seeder;

class InsuranceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            ['en' => 'Comprehensive', 'ar' => 'Comprehensive'],
            ['en' => 'Third Party', 'ar' => 'Third Party'],
        ];
        $languages = Language::all();

        for($i = 1; $i <= 1; $i++){
            $insurance = new Insurance();
            $insurance->insurance_order = $i + 1;
            $insurance->save();

            foreach($languages as $language){
                $insurance_translations = new InsuranceTranslation();
                $insurance_translations->insurance_id = $insurance->id;
                $insurance_translations->name = $array[$i][$language->language_code];
                $insurance_translations->locale = $language->language_code;
                $insurance_translations->save();
            }
        }
    }
}
