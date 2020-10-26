<?php

use App\Language;
use App\Option;
use App\OptionTranslation;
use Illuminate\Database\Seeder;

class OptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            ['en' => 'Air Conditioning', 'ar' => 'Air Conditioning'],
            ['en' => 'Alloy Wheels', 'ar' => 'Alloy Wheels'],
            ['en' => 'AM/FM Radio', 'ar' => 'AM/FM Radio'],
            ['en' => 'Driver Air Bag', 'ar' => 'Driver Air Bag'],
            ['en' => 'Passenger Air Bag', 'ar' => 'Passenger Air Bag'],
            ['en' => 'Side Air Bag', 'ar' => 'Side Air Bag'],
            ['en' => 'Anti-Lock Brakes', 'ar' => 'Anti-Lock Brakes'],
            ['en' => 'Power Steering', 'ar' => 'Power Steering'],
            ['en' => 'Cruise Control', 'ar' => 'Cruise Control'],
            ['en' => 'Leather Seats', 'ar' => 'Leather Seats'],
            ['en' => 'Power Seats', 'ar' => 'Power Seats'],
            ['en' => 'Power Windows', 'ar' => 'Power Windows'],
            ['en' => 'Rear Window Defroster', 'ar' => 'Rear Window Defroster'],
            ['en' => 'Power Door Locks', 'ar' => 'Power Door Locks'],
            ['en' => 'Tinted Glass', 'ar' => 'Tinted Glass'],
            ['en' => 'CD Player', 'ar' => 'CD Player'],
            ['en' => 'CD Changer', 'ar' => 'CD Changer'],
            ['en' => 'DVD Player', 'ar' => 'DVD Player'],
            ['en' => 'Bluetooth', 'ar' => 'Bluetooth'],
            ['en' => 'Rear Screens', 'ar' => 'Rear Screens'],
            ['en' => 'Power Mirrors', 'ar' => 'Power Mirrors'],
            ['en' => 'Heated Seats', 'ar' => 'Heated Seats'],
            ['en' => 'Sunroof/MoonRoof', 'ar' => 'Sunroof/MoonRoof'],
            ['en' => 'Navigation System', 'ar' => 'Navigation System'],
        ];
        $languages = Language::all();

        for($i = 1; $i <= 23; $i++){
            $option = new Option();
            $option->vehicle_type_id = 1;
            $option->option_order = $i + 1;
            $option->save();

            foreach($languages as $language){
                $option_translations = new OptionTranslation();
                $option_translations->option_id = $option->id;
                $option_translations->name = $array[$i][$language->language_code];
                $option_translations->locale = $language->language_code;
                $option_translations->save();
            }
        }
    }
}
