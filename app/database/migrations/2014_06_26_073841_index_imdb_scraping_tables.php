<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IndexImdbScrapingTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('imdb_scrapes', function($table) {
			$table->foreign('imdb_title_id')->references('id')->on('imdb_titles');
		});

		Schema::table('imdb_scrape_logs', function($table) {
			$table->foreign('imdb_scrape_id')->references('id')->on('imdb_scrapes');
			$table->index('instance');
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
