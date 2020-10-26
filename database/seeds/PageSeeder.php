<?php

use App\Page;
use App\PageTranslation;
use App\Language;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            [
                'page_name'  => 'Terms & Condition',
                'slug'       => 'terms-condition',
                'page_order' => 1,
            ],
            [
                'page_name'  => 'Cancellation Policy',
                'slug'       => 'cancellation-policy',
                'page_order' => 2,
            ],
            [
                'page_name'  => 'privacy',
                'slug'       => 'privacy',
                'page_order' => 3,
            ],
        ];

        $languages = Language::all();

        foreach($array as $value){
            $page = new Page();
            $page->page_name = $value['page_name'];
            $page->slug = $value['slug'];
            $page->page_order = $value['page_order'];
            $page->save();

            foreach($languages as $language){
                PageTranslation::create([
                    'page_id'     => $page->id,
                    'locale'      => $language->language_code,
                    'name'        => 'Terms & Condition',
                    'description' => 'Terms & Condition description',
                ]);

            }
        }


    }
}
