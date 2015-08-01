<?php

use Carbon\Carbon;
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
                'start_date' => Carbon::create($year, 8, 20)->toIso8601String(),
                'end_date' => Carbon::create($year, 12, 31)->toIso8601String()
            ]);

            DB::table('terms')->insert([
                'start_date' => Carbon::create($year + 1, 1, 1)->toIso8601String(),
                'end_date' => Carbon::create($year + 1, 5, 31)->toIso8601String()
            ]);
        }
    }
}
