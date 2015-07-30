<?php

use Illuminate\Database\Seeder;

use App\AgendaItem;

class AgendaItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $agendaItem = new AgendaItem();

        $agendaItem->body = 'Should the PR committee get PR branded SSE swag?';
        $agendaItem->group_id = 1;

        $agendaItem->save();
    }
}
