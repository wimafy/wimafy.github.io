<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
			$table->increments('profileID');
			$table->integer('userID')->index();
			$table->string('username');
			$table->string('FirstName');
			$table->string('LastName');
			$table->integer('PhoneNumber');
			$table->string('city');
			$table->string('state');
			$table->date('birthday');
			$table->enum('gender', ['m','f']);
			$table->string('interests');
			$table->string('bio');
			$table->string('ProfilePic');
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
        Schema::drop('profiles');
    }
}

