<?php

use Illuminate\Database\Seeder;

use App\Term;

class TermTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(range(2015, 2030) as $year) {
            DB::table('terms')->insert([
                'start_date' => date_create('08/20/' . $year),
                'end_date' => date_create('12/31/' . $year)
            ]);

            DB::table('terms')->insert([
                'start_date' => date_create('01/01/' . ($year + 1)),
                'end_date' => date_create('05/31/' . ($year + 1))
            ]);
        }
    }
}
