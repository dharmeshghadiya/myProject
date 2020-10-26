<?php

use Illuminate\Database\Seeder;
use App\Gearbox;
use App\GearboxTranslation;
use App\Language;

class GearBoxTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            ['en' => 'Automatic', 'ar' => 'تلقائي'],
            ['en' => 'Continuously variable transmission (CVT)', 'ar' => 'انتقال متغير باستمرار (CVT)'],
            ['en' => 'Electronic', 'ar' => 'إلكتروني'],
            ['en' => 'Manual', 'ar' => 'كتيب'],
        ];
        $languages = Language::all();

        for($i = 1; $i <= 3; $i++){
            $gearbox = new Gearbox();
            $gearbox->gearbox_order = $i + 1;
            $gearbox->save();

            foreach($languages as $language){
                $gearbox_translations = new GearboxTranslation();
                $gearbox_translations->gearbox_id = $gearbox->id;
                $gearbox_translations->name = $array[$i][$language->language_code];
                $gearbox_translations->locale = $language->language_code;
                $gearbox_translations->save();
            }
        }
    }
}
