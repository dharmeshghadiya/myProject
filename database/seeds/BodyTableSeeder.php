<?php

use App\Language;
use Illuminate\Database\Seeder;
use App\Body;
use App\BodyTranslation;

class BodyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            ['en' => 'Cabriolet', 'ar' => 'كابريوليه'],
            ['en' => 'Coupe', 'ar' => 'كوبيه'],
            ['en' => 'Crossover', 'ar' => 'عبور'],
            ['en' => 'Fastback', 'ar' => 'فاستباك'],
            ['en' => 'Hardtop', 'ar' => 'سطح صلب'],
            ['en' => 'Hatchback', 'ar' => 'هاتشباك'],
            ['en' => 'Liftback', 'ar' => 'رفع الظهر'],
            ['en' => 'Limousine', 'ar' => 'ليموزين'],
            ['en' => 'Minivan', 'ar' => 'حافلة صغيرة'],
            ['en' => 'Pickup', 'ar' => 'امسك'],
            ['en' => 'Roadster', 'ar' => 'رودستر'],
            ['en' => 'Sedan', 'ar' => 'ثم'],
            ['en' => 'Targa', 'ar' => 'تارجا'],
            ['en' => 'Wagon', 'ar' => 'عربة'],
        ];
        $languages = Language::all();

        for($i = 0; $i <= 13; $i++){
            $body = new Body();
            $body->vehicle_type_id = 1;
            $body->body_order = $i+1;
            $body->save();

            foreach($languages as $language){
                $body_translations = new BodyTranslation();
                $body_translations->body_id = $body->id;
                $body_translations->name = $array[$i][$language->language_code];
                $body_translations->locale = $language->language_code;
                $body_translations->save();
            }
        }
    }
}
