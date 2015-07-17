<?php

use Illuminate\Database\Seeder;

use App\Quote;

class QuoteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $quote = new Quote();

        $quote->member_id = 1;
        $quote->content = 'Arch is the best distro ever.';

        $quote->save();
    }
}
