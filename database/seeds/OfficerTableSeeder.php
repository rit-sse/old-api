<?php

use Illuminate\Database\Seeder;

class OfficerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Officer', 50)->create()->each(function ($officer) {
            $officer->save();
        });
    }
}
