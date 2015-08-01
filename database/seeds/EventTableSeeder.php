<?php

use Carbon\Carbon;
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

        $event->description = 'Crack and solve puzzles to find the answer.';
        $event->end_date = Carbon::now()->toIso8601String();
        $event->featured = true;
        $event->image = 'heist.png';
        $event->group_id = 1;
        $event->location = 'GOL-1670';
        $event->name = 'The Heist';
        $event->short_description = 'Crack and solve puzzles to find the answer.';
        $event->short_name = 'Heist';
        $event->start_date = Carbon::now()->toIso8601String();

        $event->save();
    }
}
