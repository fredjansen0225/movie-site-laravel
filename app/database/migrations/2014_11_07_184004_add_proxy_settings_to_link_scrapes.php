<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProxySettingsToLinkScrapes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('link_scrapes', function($table) {
			$table->boolean('is_proxied')->default(true)->after('is_complete');
			$table->string('proxy_used')->nullable()->after('is_proxied');
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
			$table->dropColumn('is_proxied');
			$table->dropColumn('proxy_used');
		});
	}

}
