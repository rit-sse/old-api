<?php

use Illuminate\Database\Seeder;

use App\Lingo;

class LingoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lingo = new Lingo();

        $lingo->phrase = '261';
        $lingo->definition = '261 is the course number for Introduction to ' .
            'Software Engineering, the SE course required by many programs ' .
            'here at RIT.';

        $lingo->save();
    }
}
