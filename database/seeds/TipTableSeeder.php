<?php

use Illuminate\Database\Seeder;

class TipTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Tip', 50)->create()->each(function ($tip) {
            $tip->save();
        });
    }
}
