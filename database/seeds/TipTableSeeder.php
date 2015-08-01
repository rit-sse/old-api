<?php

use Illuminate\Database\Seeder;

use App\Tip;

class TipTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tip = new Tip();

        $tip->body = 'Looking for exam resources? Besides our review ' . 
            'session (which are fantabulous), we also have a test cabinet in ' .
            'the lab (GOL-1670)!';
        $tip->member_id = 1;

        $tip->save();
    }
}
