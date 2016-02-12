<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkScraperTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('link_scrapes', function($table) {
			$table->bigIncrements('id');
			$table->bigInteger('link_id')->unsigned()->nullable();
			$table->string('provider')->default('primewire')->nullable();
			$table->bigInteger('title_id')->unsigned()->nullable();
			$table->bigInteger('season')->unsigned()->nullable();
			$table->bigInteger('episode')->unsigned()->nullable();
			$table->dateTime('started_at')->nullable();
			$table->dateTime('ended_at')->nullable();
			$table->boolean('is_successful')->default(false);
			$table->boolean('is_complete')->defult(false);
			$table->timestamps();
		});

		Schema::create('link_scrape_logs', function($table) {
			$table->bigIncrements('id');
			$table->bigInteger('link_scrape_id')->unsigned()->nullable();
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
