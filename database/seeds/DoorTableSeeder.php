<?php

use Illuminate\Database\Seeder;
use App\Door;
use App\DoorTranslation;
use App\Language;

class DoorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            ['en' => '2 Doors', 'ar' => '2 Doors'],
            ['en' => '3 Doors', 'ar' => '3 Doors'],
            ['en' => '4 Doors', 'ar' => '4 Doors'],
            ['en' => '5 Doors', 'ar' => '5 Doors'],
            ['en' => '6 Doors', 'ar' => '6 Doors'],
        ];
        $languages = Language::all();

        for($i = 0; $i <= 4; $i++){
            $door = new Door();
            $door->vehicle_type_id = 1;
            $door->door_order = $i+1;
            $door->save();

            foreach($languages as $language){
                $door_translations = new DoorTranslation();
                $door_translations->door_id = $door->id;
                $door_translations->name = $array[$i][$language->language_code];
                $door_translations->locale = $language->language_code;
                $door_translations->save();
            }
        }
    }
}
