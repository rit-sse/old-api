<?php

use Illuminate\Database\Seeder;

class AgendaItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\AgendaItem', 50)->create()->each(function ($agendaItem) {
            $agendaItem->save();
        });
    }
}
