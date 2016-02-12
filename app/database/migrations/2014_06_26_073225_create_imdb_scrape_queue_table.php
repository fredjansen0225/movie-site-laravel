<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImdbScrapeQueueTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('imdb_scrapes', function($table) {
			$table->bigIncrements('id');
			$table->bigInteger('imdb_title_id')->unsigned();
			$table->dateTime('start_time')->nullable();
			$table->dateTime('end_time')->nullable();
			$table->boolean('is_successful')->default(false);
			$table->boolean('is_complete')->default(false);
			$table->integer('priority')->default(0);
			$table->timestamps();
		});

		Schema::create('imdb_scrape_logs', function($table) {
			$table->bigIncrements('id');
			$table->bigInteger('imdb_scrape_id')->unsigned()->nullable();
			$table->string('instance')->nullable();
			$table->string('title')->nullable();
			$table->text('message')->nullable();
			$table->timestamps();
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
