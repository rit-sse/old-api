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

        $lingo = new Lingo();

        $lingo->phrase = '361';
        $lingo->definition = '361 is the course number for the old Introduction ' .
            'to Software Engineering, the SE course required by many programs ' .
            'RIT back in the glory days of the quarter system.';

        $lingo->save();
    }
}
