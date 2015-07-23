<?php

use Illuminate\Database\Seeder;

use App\Membership;

class MembershipTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $membership = new Membership();

        $membership->member_id = 1;
        $membership->reason = 'Bought SSE Gold';
        $membership->term_id = 1;

        $membership->save();
    }
}
