<?php

use Illuminate\Database\Seeder;

class LingoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Lingo', 50)->create()->each(function ($lingo) {
            $lingo->save();
        });
    }
}
