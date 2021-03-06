<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddUsersFields extends Migration {

	public function up(){
		Schema::table('users', function(Blueprint $table){
			$table->integer('sex')->unsigned()->default(0)->nullable();
			$table->string('phone', 20)->nullable();
			$table->string('city', 50)->nullable();
			$table->timestamp('bdate')->nullable();

			$table->integer('remote_id')->after('id')->unsigned()->default(0)->nullable();
			$table->text('sessionKey')->nullable();
			$table->integer('winner')->unsigned()->default(0)->nullable();
			$table->integer('number_week')->unsigned()->default(0)->nullable();
			$table->integer('total_likes')->unsigned()->default(0)->nullable();
			$table->string('total_extend', 100)->nullable();
		});
	}

	public function down(){
		Schema::table('users', function(Blueprint $table){
			$table->dropColumn('sex');
			$table->dropColumn('phone');
			$table->dropColumn('bdate');
			$table->dropColumn('remote_id');
			$table->dropColumn('sessionKey');
			$table->dropColumn('winner');
			$table->dropColumn('number_week');
			$table->dropColumn('total_likes');
			$table->dropColumn('total_extend');
		});
	}
}
