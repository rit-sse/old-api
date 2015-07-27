<?php

use Illuminate\Database\Seeder;

use App\Member;

class MemberTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $member = new Member();

        $member->first_name = 'John';
        $member->last_name = 'Doe';
        $member->username = 'jxd1234@g.rit.edu';

        $member->save();
    }
}
