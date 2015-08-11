<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserCodesTable extends Migration {

	public function up(){
		Schema::create('user_codes', function(Blueprint $table){
			$table->increments('id');
			$table->integer('user_id')->unsigned()->default(0)->nullable();
			$table->integer('code_number')->unsigned()->default(0)->nullable();
			$table->string('code',10)->nullable();
			$table->timestamps();
		});
	}

	public function down(){
		Schema::drop('user_codes');
	}

}
