<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExternalProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_profiles', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('member_id')->unsigned();
            $table->string('provider');
            $table->string('identifier');

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
        Schema::drop('external_profiles');
    }
}
