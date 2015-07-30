<?php

use Illuminate\Database\Seeder;

use App\Event;

class EventTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $event = new Event();

        $event->group_id = 1;

        $event->save();
    }
}
