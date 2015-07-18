<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Officer;

class CreateOfficersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('officers', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('member_id')->unsigned();
            $table->string('position');
            $table->integer('term_id')->unsigned();

            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('term_id')->references('id')->on('terms');

            $table->softDeletes();
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
        Schema::drop('officers');
    }
}
