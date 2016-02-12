<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkClicksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('link_clicks', function($table) {
			$table->bigIncrements('id');
			$table->bigInteger('link_id')->unsigned();
			$table->string('ip_address')->nullable();
			$table->timestamps();
		});

		Schema::table('link_clicks', function($table) {
			$table->foreign('link_id')->references('id')->on('links');
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
