<?php

use App\Category;
use App\CategoryTranslation;
use App\Language;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            ['en' => 'Drive too fast\nDrive too furious ', 'ar' => 'Drive too fast\nDrive too furious'],
            ['en' => 'they see me rollin\nthey hatin', 'ar' => 'they see me rollin\nthey hatin'],
            ['en' => 'Drive too fast\nDrive too furious 1', 'ar' => 'Drive too fast\nDrive too furious 1'],
            ['en' => 'they see me rollin\nthey hatin 1', 'ar' => 'they see me rollin\nthey hatin 1'],
        ];
        $languages = Language::all();

        for($i = 1; $i <= 3; $i++){
            $category = new Category();
            $category->category_order = $i + 1;
            $category->image = 'uploads/default/category/' . $i . '.png';
            $category->save();

            foreach($languages as $language){
                $category_translations = new CategoryTranslation();
                $category_translations->category_id = $category->id;
                $category_translations->name = $array[$i][$language->language_code];
                $category_translations->locale = $language->language_code;
                $category_translations->save();
            }
        }
    }
}
