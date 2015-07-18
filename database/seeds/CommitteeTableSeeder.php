<?php

use Illuminate\Database\Seeder;

use App\Committee;

class CommitteeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $committee = new Committee();

        $committee->name = 'Projects';
        $committee->head_id = 1;

        $committee->save();

        $committee->members()->attach(1);
    }
}
