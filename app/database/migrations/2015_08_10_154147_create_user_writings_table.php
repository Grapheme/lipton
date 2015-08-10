<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserWritingsTable extends Migration {

	public function up(){
		Schema::create('user_writings', function(Blueprint $table){
			$table->increments('id');
			$table->integer('user_id')->unsigned()->default(0)->nullable();
			$table->text('writing')->nullable();
			$table->tinyInteger('status')->unsigned()->default(0)->nullable();
			$table->timestamps();
		});
	}

	public function down(){
		Schema::drop('user_writings');
	}

}
