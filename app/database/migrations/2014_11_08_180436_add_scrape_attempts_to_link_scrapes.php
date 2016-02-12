<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScrapeAttemptsToLinkScrapes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('link_scrapes', function($table) {
			$table->integer('attempts')->default(0)->after('ended_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('link_scrapes', function($table) {
			$table->dropColumn('attempts');
		});
	}

}
