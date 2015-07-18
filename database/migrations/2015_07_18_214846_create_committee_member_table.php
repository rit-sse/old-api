<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommitteeMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('committee_member', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('committee_id')->unsigned();
            $table->integer('member_id')->unsigned();

            $table->foreign('committee_id')->references('id')->on('committees');
            $table->foreign('member_id')->references('id')->on('members');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('committee_member');
    }
}
