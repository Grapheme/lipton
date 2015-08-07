<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddUsersFields extends Migration {

	public function up(){
		Schema::table('users', function(Blueprint $table){
			$table->integer('sex')->unsigned()->default(0)->nullable();
			$table->string('phone', 20)->nullable();
			$table->timestamp('bdate')->nullable();
		});
	}

	public function down(){
		Schema::table('users', function(Blueprint $table){
			$table->dropColumn('sex');
			$table->dropColumn('phone');
			$table->dropColumn('bdate');
		});
	}
}
