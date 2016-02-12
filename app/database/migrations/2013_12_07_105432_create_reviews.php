<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviews extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reviews', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->string('author', 255);
			$table->string('source', 255)->nullable();
			$table->text('body')->nullable();
			$table->integer('score')->nullable();
			$table->string('link', 255)->nullable();
			$table->bigInteger('title_id')->unsigned();
			$table->timestamp('created_at')->default( DB::raw('CURRENT_TIMESTAMP') );
			$table->timestamp('updated_at')->nullable();			
			$table->string('temp_id', 255)->nullable();

			$table->engine = 'InnoDB';
			$table->unique(array('title_id','author'), 'author_title_unique');
			$table->foreign('title_id')->references('id')->on('titles')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('reviews');
	}

}
