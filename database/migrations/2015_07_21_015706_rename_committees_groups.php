<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameCommitteesGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('committees', 'groups');

        Schema::create('group_member', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('group_id')->unsigned();
            $table->integer('member_id')->unsigned();

            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('member_id')->references('id')->on('members');

            $table->timestamps();
        });

        $committee_members = \DB::table('committee_member')->get();

        foreach($committee_members as $committee_member) {
            \DB::table('group_member')->insert([
                'group_id' => $committee_member->committee_id,
                'member_id' => $committee_member->member_id,
                'created_at' => $committee_member->created_at,
                'updated_at' => $committee_member->updated_at,
            ]);
        }

        Schema::drop('committee_member');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('groups', 'commitees');

        Schema::create('committee_member', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('committee_id')->unsigned();
            $table->integer('member_id')->unsigned();

            $table->foreign('committee_id')->references('id')->on('committees');
            $table->foreign('member_id')->references('id')->on('members');

            $table->timestamps();
        });

        $group_members = \DB::table('group_member')->get();

        foreach($group_members as $group_member) {
            \DB::table('group_member')->insert([
                'committee_id' => $group_member->committee_id,
                'member_id' => $group_member->member_id,
                'created_at' => $group_member->created_at,
                'updated_at' => $group_member->updated_at,
            ]);
        }

        Schema::drop('group_member');
    }
}
