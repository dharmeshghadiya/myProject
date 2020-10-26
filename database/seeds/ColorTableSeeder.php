<?php

use App\Language;
use App\Color;
use App\ColorTranslation;
use Illuminate\Database\Seeder;

class ColorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            ['en' => 'Copperhead Pearl', 'ar' => 'Copperhead Pearl'],
            ['en' => 'Western Brown', 'ar' => 'Western Brown'],
            ['en' => 'True Blue', 'ar' => 'True Blue'],
            ['en' => 'Black Gold', 'ar' => 'Black Gold'],
            ['en' => 'Prairie Pearl Coat', 'ar' => 'Prairie Pearl Coat'],
            ['en' => 'Case IH Red', 'ar' => 'Case IH Red'],
            ['en' => 'Construction Yellow', 'ar' => 'Construction Yellow'],
            ['en' => 'Opulent Blue', 'ar' => 'Opulent Blue'],
            ['en' => 'Silver Coast', 'ar' => 'Silver Coast'],
            ['en' => 'Glacier Blue', 'ar' => 'Glacier Blue'],
            ['en' => 'Mocha Steel', 'ar' => 'Mocha Steel'],
            ['en' => 'Radiant Silver', 'ar' => 'Radiant Silver'],
            ['en' => 'Sapphire Blue', 'ar' => 'Sapphire Blue'],
            ['en' => 'Black Ice', 'ar' => 'Black Ice'],
            ['en' => 'Tuxedo Black', 'ar' => 'Tuxedo Black'],
            ['en' => 'Green Envy', 'ar' => 'Green Envy'],
            ['en' => 'Performance Blue', 'ar' => 'Performance Blue'],
            ['en' => 'Gotta Have It Green', 'ar' => 'Gotta Have It Green'],
            ['en' => 'Blue Granite', 'ar' => 'Blue Granite'],
            ['en' => 'Tungsten Metallic', 'ar' => 'Tungsten Metallic'],
            ['en' => 'Blue Topaz', 'ar' => 'Blue Topaz'],
            ['en' => 'Tanzanite Blue', 'ar' => 'Tanzanite Blue'],
            ['en' => 'Moonstone Metallic', 'ar' => 'Moonstone Metallic'],
            ['en' => 'Deep Sea Blue', 'ar' => 'Deep Sea Blue'],
            ['en' => 'Mojave Metallic', 'ar' => 'Mojave Metallic'],
            ['en' => 'Space Gray', 'ar' => 'Space Gray'],
            ['en' => 'Dolomite Brown', 'ar' => 'Dolomite Brown'],
            ['en' => 'Iridium Silver', 'ar' => 'Iridium Silver'],
            ['en' => 'Palladium Silver', 'ar' => 'Palladium Silver'],
            ['en' => 'Lunar Blue', 'ar' => 'Lunar Blue'],
        ];
        $languages = Language::all();

        for($i = 1; $i <= 29; $i++){
            $color = new Color();
            $color->color_order = $i + 1;
            $color->save();

            foreach($languages as $language){
                $color_translations = new ColorTranslation();
                $color_translations->color_id = $color->id;
                $color_translations->name = $array[$i][$language->language_code];
                $color_translations->locale = $language->language_code;
                $color_translations->save();
            }
        }
    }
}
