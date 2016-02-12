<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImdbTitles extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('imdb_titles', function(Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('imdb_id')->nullable();
			$table->string('title_name')->nullable();
			$table->string('original_title')->nullable();
			$table->string('type')->nullable();
			$table->text('poster')->nullable();
			$table->smallInteger('year')->nullable();
			$table->text('plot')->nullable();
			$table->string('genre')->nullable();
			$table->string('imdb_rating')->nullable();
			$table->string('runtime')->nullable();
			$table->bigInteger('imdb_votes_num')->nullable();
			$table->dateTime('last_scraped_at')->nullable();
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
