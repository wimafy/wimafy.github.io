<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wims', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('user_id')->index();
            $table->string('title');
            $table->string('location');
            $table->string('address');
            $table->dateTime('date');
            $table->string('attire');
            $table->string('description');
            $table->timestamps();
        });
    }
	
}
