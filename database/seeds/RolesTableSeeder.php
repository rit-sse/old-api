<?php

use Illuminate\Database\Seeder;

use App\Member;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Member is an administrator',
        ]);

        $member = Role::firstOrCreate([
            'name' => 'member',
            'display_name' => 'Member',
            'description' => 'Generic membership role',
        ]);

        $mentor = Role::firstOrCreate([
            'name' => 'mentor',
            'display_name' => 'Mentor',
            'description' => 'Member is a mentor',
        ]);

        $officer = Role::firstOrCreate([
            'name' => 'officer',
            'display_name' => 'Officer',
            'description' => 'Member is an officer',
        ]);

        if (\App::environment('local')) {
            $member = Member::findOrFail(1);
            $member->attachRole($admin);
        }
    }
}
