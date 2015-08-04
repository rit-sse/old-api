<?php

use Illuminate\Database\Seeder;

use App\Member;
use App\Permission;
use App\Role;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            'agenda',
            'events',
            'groups',
            'lingo',
            'links',
            'members',
            'memberships',
            'mentors',
            'officers',
            'quotes',
            'terms',
            'tips'
        ];

        $all = [];

        $admin = Role::where('name', '=', 'admin')->firstOrFail();
        $member = Role::where('name', '=', 'member')->firstOrFail();
        $mentor = Role::where('name', '=', 'mentor')->firstOrFail();
        $officer = Role::where('name', '=', 'officer')->firstOrFail();

        foreach($array as $controller) {
            $create = Permission::firstOrCreate([
                'name' => 'api.v1.' . $controller . '.store',
                'display_name' => 'Create ' . $controller,
                'description' => 'create a ' . $controller,
            ]);

            if(in_array($controller, ['agenda', 'events', 'lingo', 'links', 'quotes', 'tips'])) {
                $create->level = 100;
                $create->save();
            }

            $update = Permission::firstOrCreate([
                'name' => 'api.v1.' . $controller . '.update',
                'display_name' => 'Update ' . $controller,
                'description' => 'update a ' . $controller,
            ]);

            $destroy = Permission::firstOrCreate([
                'name' => 'api.v1.' . $controller . '.destroy',
                'display_name' => 'Destroy ' . $controller,
                'description' => 'destroy a ' . $controller,
            ]);

            $all[$controller] = [
                'create' => $create,
                'update' => $update,
                'destroy' => $destroy,
            ];

            $admin->attachPermissions([$create, $update, $destroy]);

            if($controller != "officer") {
                $officer->attachPermissions([$create, $update, $destroy]);
            }


        }
        $member->attachPermission($all['quotes']['create']);
    }
}
