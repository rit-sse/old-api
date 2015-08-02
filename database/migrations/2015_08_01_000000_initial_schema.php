<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InitialSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');

            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('external_profiles', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('member_id')->unsigned();
            $table->string('provider');
            $table->string('identifier');

            $table->foreign('member_id')->references('id')->on('members');

            $table->timestamps();
        });

        Schema::create('terms', function (Blueprint $table) {
            $table->increments('id');

            $table->date('start_date');
            $table->date('end_date');
        });

        Schema::create('memberships', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('member_id')->unsigned();
            $table->integer('term_id')->unsigned();
            $table->string('reason');

            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('term_id')->references('id')->on('terms');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('officers', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('member_id')->unsigned();
            $table->integer('term_id')->unsigned();
            $table->string('title');

            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('term_id')->references('id')->on('terms');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');

            $table->string('body');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('quotes', function (Blueprint $table) {
            $table->increments('id');

            $table->boolean('approved')->default(false);
            $table->string('description');
            $table->integer('member_id')->unsigned();
            $table->string('body');

            $table->foreign('member_id')->references('id')->on('member');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('lingo', function (Blueprint $table) {
            $table->increments('id');

            $table->string('phrase');
            $table->string('definition');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('tips', function (Blueprint $table) {
            $table->increments('id');

            $table->string('body');
            $table->integer('member_id')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();

            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('updated_by')->references('id')->on('members');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->integer('officer_id')->unsigned();

            $table->foreign('officer_id')->references('id')->on('officers');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('group_member', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('group_id')->unsigned();
            $table->integer('member_id')->unsigned();

            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('member_id')->references('id')->on('members');

            $table->timestamps();
        });

        Schema::create('agenda_items', function (Blueprint $table) {
            $table->increments('id');

            $table->string('body');
            $table->integer('group_id')->unsigned();

            $table->foreign('group_id')->references('id')->on('groups');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('links', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('member_id')->unsigned();
            $table->string('expanded_link');
            $table->string('go_link');

            $table->foreign('member_id')->references('id')->on('members');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');

            $table->string('description');
            $table->datetime('end_date');
            $table->boolean('featured');
            $table->integer('group_id')->unsigned();
            $table->string('image');
            $table->string('location');
            $table->string('name');
            $table->string('recurrence')->nullable();
            $table->string('short_description');
            $table->string('short_name');
            $table->datetime('start_date');

            $table->foreign('group_id')->references('id')->on('groups');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('description');
            $table->boolean('completed');
            $table->integer('creator_id')->unsigned();
            $table->integer('assignee_id')->unsigned();

            $table->foreign('creator_id')->references('id')->on('members');
            $table->foreign('assignee_id')->references('id')->on('members');

            $table->softDeletes();
            $table->timestamps();
        });

        /**
         * Roles and Permissions Tables
         */

        // Create table for storing roles
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for associating roles to users (Many-to-Many)
        Schema::create('role_member', function (Blueprint $table) {
            $table->integer('member_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('member_id')->references('id')->on('members')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['member_id', 'role_id']);
        });

        // Create table for storing permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('permission_role', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('members');
        Schema::drop('external_profiles');
        Schema::drop('terms');
        Schema::drop('memberships');
        Schema::drop('officers');
        Schema::drop('tags');
        Schema::drop('quotes');
        Schema::drop('lingo');
        Schema::drop('tips');
        Schema::drop('groups');
        Schema::drop('group_member');
        Schema::drop('agenda_items');
        Schema::drop('links');
        Schema::drop('events');
        Schema::drop('tasks');
        Schema::drop('roles');
        Schema::drop('role_member');
        Schema::drop('permissions');
        Schema::drop('permission_role');
    }
}
