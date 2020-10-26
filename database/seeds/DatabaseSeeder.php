<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(VehicleTypeSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(LanguageStringTableSeeder::class);
        $this->call(BodyTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(DoorTableSeeder::class);
        $this->call(FuelTableSeeder::class);
        $this->call(GearBoxTableSeeder::class);
        $this->call(BrandTableSeeder::class);
        $this->call(EngineTableSeeder::class);
        $this->call(ColorTableSeeder::class);
        $this->call(ModelYearTableSeeder::class);
        $this->call(CountryTableSeeder::class);
        $this->call(InsuranceTableSeeder::class);
        $this->call(OptionTableSeeder::class);
        $this->call(FeatureTableSeeder::class);
    }
}
