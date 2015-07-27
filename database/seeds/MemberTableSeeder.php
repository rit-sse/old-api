<?php

use Illuminate\Database\Seeder;

use App\ExternalProfile;
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
        $profile = new ExternalProfile();

        $profile->provider = 'slack';
        $profile->identifier = 'U1234567890';

        $member = new Member();

        $member->first_name = 'John';
        $member->last_name = 'Doe';
        $member->email = 'jxd1234@g.rit.edu';

        $member->save();

        $member->externalProfiles()->save($profile);
    }
}
