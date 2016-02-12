<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;


class ImdbScrapeCommand extends ScheduledCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'imdb:scrape';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Scrape a specific number of titles in the queue (or for a set runtime).';

	public $instance = 'imdb_scrape';

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

		set_time_limit(0);
		ini_set('memory_limit', '256M');
		DB::connection()->disableQueryLog();

		$start = time();

		$scrapeAttempts = 0;

		function logScrape($command, $imdbScrape, $titleString, $message)
		{
			$command->info('-- '.$titleString.': '.$message);
			$log = new ImdbScrapeLog;
			$log->imdb_scrape_id = $imdbScrape->id;
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
			$imdbScrape = ImdbScrape::whereNull('start_time')->orderBy(DB::raw('RAND()'))->first();

			if(!$imdbScrape)
			{
				$this->info('Nothing to scrape...');
				break;
			}

			$imdbScrape->start_time = new DateTime('now');
			$imdbScrape->save();

			$imdbTitle = $imdbScrape->imdb_title;

			$rating = floatval(explode('/', $imdbTitle->imdb_rating)[0]);
			if($rating < 6)
			{
				logScrape($this, $imdbScrape, $imdbTitle->title_name, 'IMDB rating is less than 6. Skipping...');
			}
			elseif( ! $imdbTitle->poster)
			{
				logScrape($this, $imdbScrape, $imdbTitle->title_name, 'IMDB title does not have a poster. Skipping...');
			}
			else
			{
				logScrape($this, $imdbScrape, $imdbTitle->title_name, 'Beginning full scrape...');
				try
				{
					$scraper = App::make('\Lib\Services\Scraping\Scraper');
					$scraper->fullyScrapeImdbTitle($imdbTitle);
					logScrape($this, $imdbScrape, $imdbTitle->title_name, 'Scrape complete!');
					$imdbScrape->is_successful = true;
					
				}
				catch(\Exception $ex)
				{
					logScrape($this, $imdbScrape, $imdbTitle->title_name, 'Scraper error: '.$ex->getMessage().' File:'.$ex->getFile().':'.$ex->getLine());
					$imdbScrape->start_time = null;
					$imdbScrape->is_successful = false;
					$imdbScrape->save();
					continue;
				}
			}

			$imdbScrape->end_time = new DateTime('now');
			$imdbScrape->is_complete = true;
			$imdbScrape->save();

			$imdbTitle->last_scraped_at = new DateTime('now');
			$imdbTitle->save();

			$scrapeAttempts++;				

		}
		if($this->option('perpetual'))
		{
			$command = 'php '.__DIR__.'/../../artisan imdb:scrape > /dev/null 2>/dev/null &';
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
			array('runtime', 'rumtime', InputOption::VALUE_OPTIONAL, 'How long should this thread run in seconds?', 3600),
			array('perpetual', 'perpetual', InputOption::VALUE_OPTIONAL, 'Should it auto-restart', 0),
		);
	}

	public function schedule(Schedulable $scheduler)
    {
		return $scheduler->hourly();
    }

}
