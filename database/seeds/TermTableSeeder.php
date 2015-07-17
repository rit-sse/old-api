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
        foreach(range(2015, 50) as $year) {
            DB::table('terms')->insert([
                'name' => 'Fall',
                'year' => $year
            ]);

            DB::table('terms')->insert([
                'name' => 'Spring',
                'year' => $year
            ]);
        }
    }
}
