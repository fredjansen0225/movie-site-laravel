<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;


class ImdbScheduleScrapesCommand extends ScheduledCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'imdb:schedule-scrapes';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Schedule any scrapes that need to occur.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->info('Initializing...');
		set_time_limit(0);
		ini_set('memory_limit', '256M');
		DB::connection()->disableQueryLog();
		
		$this->info('Retrieving IMDB titles to scrape...');

		$imdbTitles = ImdbTitle::whereNull('last_scraped_at')->orWhere('last_scraped_at', '<', new DateTime('-1 week'))->get();

		$this->info('Scheduling '.$imdbTitles->count().' scrapes.');

		foreach($imdbTitles as $imdbTitle)
		{
			$imdbScrape = new ImdbScrape;
			$imdbScrape->imdb_title_id = $imdbTitle->id;
			$imdbScrape->save();
		}

		$this->info('Complete!');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(

		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(

		);
	}

	public function schedule(Schedulable $scheduler)
    {
		return $scheduler->daily();
    }

}
