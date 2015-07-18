<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(MemberTableSeeder::class);
        $this->call(TermTableSeeder::class);
        $this->call(MembershipTableSeeder::class);
        $this->call(EventTableSeeder::class);
        $this->call(LingoTableSeeder::class);
        $this->call(OfficerTableSeeder::class);
        $this->call(QuoteTableSeeder::class);
        $this->call(TipTableSeeder::class);
        $this->call(CommitteeTableSeeder::class);

        Model::reguard();
    }
}
