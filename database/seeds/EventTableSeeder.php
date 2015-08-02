<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EventTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Event', 50)->create()->each(function ($event) {
            $event->save();
        });
    }
}
