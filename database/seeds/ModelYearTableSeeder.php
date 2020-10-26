<?php

use App\ModelYear;
use Illuminate\Database\Seeder;

class ModelYearTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            2001, 2002, 2003, 2004, 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012,
            2013, 2014, 2015, 2016, 2017, 2018, 2019, 2020,
        ];
        foreach($array as $key => $value){
            $year = new ModelYear();
            $year->name = $value;
            $year->save();
        }
    }
}
