<?php

use Illuminate\Database\Seeder;

class TaskTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Task', 50)->create()->each(function ($task) {
            // Create 50 App\Task models, and save em.
            $task->save();
        });
    }
}
