<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IndexLinkTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::table('link_reports', function($table) {
			$table->foreign('link_id')->references('id')->on('links');
			$table->foreign('user_id')->references('id')->on('users');
		});

		Schema::table('link_scrapes', function($table) {
			$table->foreign('link_id')->references('id')->on('links');
		});

		Schema::table('link_scrape_logs', function($table) {
			$table->foreign('link_scrape_id')->references('id')->on('link_scrapes');
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
