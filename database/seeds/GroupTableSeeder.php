<?php

use Illuminate\Database\Seeder;

use App\Group;

class GroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $group = new Group();

        $group->name = 'Projects';
        $group->head_id = 1;

        $group->save();

        $group->members()->attach(1);
    }
}
