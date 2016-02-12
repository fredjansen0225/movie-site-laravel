<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;


class PutlockerScrapeCommand extends ScheduledCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'putlocker:scrape';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Scrape a specific number of titles in the queue (or for a set runtime).';

	public $instance = 'putlocker_scrape';

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
		$scrapeLimit = $this->option('scrape-limit');
		$runtimeLimit = $this->option('runtime');

		$i = 1;
		while($i < intval($this->option('instances')))
		{
			$command = 'php '.__DIR__.'/../../artisan putlocker:scrape --instances=1 > /dev/null 2>/dev/null &';
			shell_exec($command);
			$this->info('Forked another instance...');
			$i++;
		}

		set_time_limit(0);
		ini_set('memory_limit', '256M');
		DB::connection()->disableQueryLog();

		$start = time();

		$scrapeAttempts = 0;

		function logScrape($command, $scrape, $titleString, $message)
		{
			$command->info('-- '.$titleString.': '.$message);
			$log = new LinkpScrapeLog;
			$log->link_scrape_id = $scrape->id;
			$log->instance = $command->instance;
			$log->title = $titleString;
			$log->message = $message;
			$log->save();
		}

		function shouldContinue($runtimeLimit, $scrapeLimit, $scrapeAttempts, &$start) {
			if($runtimeLimit && (time() - $start) >= $runtimeLimit) return false;
			if($scrapeLimit && $scrapeAttempts >= $scrapeLimit) return false;
			return true;
		}

		/**
		 * @var $scraper \Lib\Services\Scraping\Scraper;
		 */
		while(shouldContinue($runtimeLimit, $scrapeLimit, $scrapeAttempts, $start))
		{
			$scrape = LinkpScrape::whereNull('started_at')->where('provider','putlocker')->orderBy(DB::raw('RAND()'))->first();

			if(!$scrape)
			{
				$this->info('Nothing to scrape...');
				break;
			}

			$scrape->started_at = new DateTime('now');
			$scrape->save();

			$title = $scrape->title;
			$year='';
			//if (is_object($title) and isset($title->year)) $year = $title->year;
			if (isset($title['year'])) $year = $title['year'];

			$scraper = App::make('Lib\Services\Scraping\PutlockerLinkScraper');

			logScrape($this, $scrape, $title['title'], 'Beginning link scrape.');

			if($title['type'] != 'series')
			{
				try
				{
					$links = $scraper->scrapeLinksForTitle($title, $year);
					logScrape($this, $scrape, $title['title'], $links.' Scraped links: 1');
				}
				catch(\Exception $ex)
				{
					logScrape($this, $scrape, $title['title'], 'Error parsing Putlocker: '.$ex->getMessage().' [File: '.$ex->getFile().':'.$ex->getLine().']');
					$scrape->started_at = null;
					$scrape->save();
					$scrapeAttempts++;				
					continue;
				}
			}
			
			$scrape->ended_at = new DateTime('now');
			$scrape->is_complete = true;
			$scrape->save();

			$scrapeAttempts++;				

		}

		if($this->option('perpetual'))
		{
			$command = 'php '.__DIR__.'/../../artisan putlocker:scrape > /dev/null 2>/dev/null &';
			shell_exec($command);			
		}
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
			array('scrape-limit', 'scrape-limit', InputOption::VALUE_OPTIONAL, 'How many titles should this thread scrape before quitting?', 0),
			array('runtime', 'rumtime', InputOption::VALUE_OPTIONAL, 'How long should this thread run?', 3600),
			array('perpetual', 'perpetual', InputOption::VALUE_OPTIONAL, 'Should it auto-restart', 0	),
			array('instances', 'instances', InputOption::VALUE_OPTIONAL, 'How many instances?', 10)
		);
	}

	public function schedule(Schedulable $scheduler)
    {
		return $scheduler->hourly();
    }

}
