<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserPrizesTable extends Migration {

	public function up(){
		Schema::create('user_prizes', function(Blueprint $table){
			$table->increments('id');
			$table->integer('user_id')->unsigned()->default(0)->nullable();
			$table->integer('prize_id')->unsigned()->default(0)->nullable();
			$table->string('title',100)->nullable();
			$table->tinyInteger('number')->unsigned()->default(0)->nullable();
			$table->timestamps();
		});
	}

	public function down(){
		Schema::drop('user_prizes');
	}

}
