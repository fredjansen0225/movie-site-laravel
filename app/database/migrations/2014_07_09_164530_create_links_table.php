<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('links', function($table) {
			$table->string('provider')->default('primewire')->after('url');
			$table->integer('rank')->default(0)->after('url');
			$table->boolean('is_working')->default(true)->after('url');
		});

		Schema::create('link_reports', function($table) {
			$table->bigIncrements('id');
			$table->bigInteger('link_id')->unsigned()->nullable();
			$table->tinyInteger('value')->default(0);
			$table->string('ip_address');
			$table->integer('user_id')->unsigned()->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
