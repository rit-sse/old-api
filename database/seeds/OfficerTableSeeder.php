<?php

use Illuminate\Database\Seeder;

use App\Officer;

class OfficerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $officer = new Officer();

        $officer->member_id = 1;
        $officer->position = 'President';
        $officer->term_id = 1;

        $officer->save();
    }
}
