<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;


class ZMovieScheduleScrapesCommand extends ScheduledCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'zmovie:schedule-scrapes';

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
		
		$this->info('Retrieving titles to scrape...');

		$i = 0;
		$titles = Title::take(50)->skip(50 * $i)->get();

		while(count($titles) > 0)
		{
			foreach($titles as $title)
			{
				$lastLinkzScrape = LinkzScrape::whereTitleId($title->id)->orderBy('started_at', 'desc')->first();
				if(!$lastLinkzScrape || $lastLinkzScrape->ended_at > new DateTime('-7 days'))
				{
					$linkzScrape = new LinkzScrape;
					$linkzScrape->provider = 'zmovie';
					$linkzScrape->title_id = $title->id;
					$linkzScrape->save();
				}
			}
			$i++;
			$titles = Title::take(50)->skip(50*$i)->get();
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
