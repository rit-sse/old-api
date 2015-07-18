<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tips', function (Blueprint $table) {
            $table->increments('id');

            $table->string('content');
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();

            $table->foreign('created_by')->references('id')->on('members');
            $table->foreign('updated_by')->references('id')->on('members');

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
        Schema::drop('tips');
    }
}
