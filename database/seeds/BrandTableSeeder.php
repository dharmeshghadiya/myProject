<?php

use App\Brand;
use App\BrandTranslation;
use App\Language;
use Illuminate\Database\Seeder;

class BrandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            ['en' => 'Maruti Suzuki', 'ar' => 'Maruti Suzuki'],
            ['en' => 'Hyundai', 'ar' => 'Hyundai'],
            ['en' => 'Mahindra', 'ar' => 'Mahindra'],
            ['en' => 'Tata', 'ar' => 'Tata'],
            ['en' => 'Toyota', 'ar' => 'Toyota'],
            ['en' => 'Renault', 'ar' => 'Renault'],
            ['en' => 'Ford', 'ar' => 'Ford'],
            ['en' => 'Nissan', 'ar' => 'Nissan'],
            ['en' => 'Volkswagen', 'ar' => 'Volkswagen'],
        ];
        $languages = Language::all();

        for($i = 1; $i <= 8; $i++){
            $brand = new Brand();
            $brand->vehicle_type_id = 1;
            $brand->brand_order = $i + 1;
            $brand->image = 'uploads/default/brand/' . $i . '.png';
            $brand->save();

            foreach($languages as $language){
                $brand_translations = new BrandTranslation();
                $brand_translations->brand_id = $brand->id;
                $brand_translations->name = $array[$i][$language->language_code];
                $brand_translations->locale = $language->language_code;
                $brand_translations->save();
            }
        }
    }
}
