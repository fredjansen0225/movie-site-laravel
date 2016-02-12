<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Lib\Services\Imdb\MovieListReader;

use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;


class ImdbUpdateMovieListCommand extends ScheduledCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'imdb:update-imdb-titles';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Scrape IMDB title metadata by year, used for later full scrapes.';

	public $instance = 'update_imdb_titles';

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
		set_time_limit(0);
		ini_set('memory_limit', '256M');
		$start = time();

		// This saves a lot of memory
		DB::connection()->disableQueryLog();

		function logScrape($command, $imdbScrape, $titleString, $message)
		{
			$command->info('-- '.$titleString.': '.$message);
			$log = new ImdbScrapeLog;
			$log->instance = $command->instance;
			$log->imdb_scrape_id = $imdbScrape ? $imdbScrape->id : null;
			$log->title = $titleString;
			$log->message = $message;
			$log->save();
		}
		function shouldContinue($runtimeLimit, $scrapeLimit, $scrapeAttempts, &$start) {
			if($runtimeLimit && (time() - $start) >= $runtimeLimit) return false;
			if($scrapeLimit && $scrapeAttempts >= $scrapeLimit) return false;
			return true;
		}

		$this->info('');

		logScrape($this, null, 'System', 'Starting to update list...');

		$processedCount = 0;

		$year = $this->option('start-year');
		$searchCount = 0;
		$curl = App::make('Lib\Services\Scraping\Curl');

		$imdbSearch = new Lib\Services\Search\ImdbSearch;
			
		while($year >= 1900 && (!$this->option('max-years') || ($this->option('start-year') - $year <= $this->option('max-years'))) && shouldContinue($this->option('runtime-limit'), $this->option('scrape-limit'), $processedCount, $start))
		{
			
			$searchUrl = 'http://www.imdb.com/search/title?at=0&count=100&sort=moviemeter,asc&start='.($searchCount*100 + 1).'&title_type=feature,tv_series&year='.$year;

			$searchCount++;

			logScrape($this, null, 'System', 'Requesting batch #'.$searchCount.' for year '.$year.'...');
			$results = $curl->curl($searchUrl);

			logScrape($this, null, 'System', 'Generating usable list from HTML response.');
			$results = $imdbSearch->compileSearchResults($results);

			if(count($results) > 0)
			{
				foreach($results as $result)
				{

					$existed = true;
					$imdbTitle = ImdbTitle::where('imdb_id', '=', $result['imdb_id'])->first();
					if(!$imdbTitle)
					{
						$existed = false;
						$imdbTitle = new ImdbTitle;
					}
					$imdbTitle->fill($result);
					$imdbTitle->title_name = $result['title'];
					$imdbTitle->save();

					if(!$existed)
					{
						$imdbScrape = new ImdbScrape;
						$imdbScrape->imdb_title_id = $imdbTitle->id;
						$imdbScrape->priority = 1;
						$imdbScrape->save();
						logScrape($this, null, $imdbTitle->title_name, 'Updated IMDB search cache & scheduled scrape.');						
					}
					else
					{
						$imdbScrape = new ImdbScrape;
						$imdbScrape->imdb_title_id = $imdbTitle->id;
						$imdbScrape->priority = 1;
						$imdbScrape->save();
						logScrape($this, null, $imdbTitle->title_name, 'Updated IMDB search cache');						
					}
				}
			}
			else
			{
				$searchCount = 0;
				$year--;
			}
		}

		logScrape($this, null, 'System', 'IMDB list sync complete. Shutting down...');

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
			array('scrape-limit', 'scrape-limit', InputOption::VALUE_OPTIONAL, 'How many titles should this thread update before quitting?', 0),
			array('runtime-limit', 'rumtime-limit', InputOption::VALUE_OPTIONAL, 'How long should this thread run in seconds?', 3600),
			array('start-year', 'start-year', InputOption::VALUE_OPTIONAL, 'Start Year', date('Y')),
			array('max-years', 'max-years', InputOption::VALUE_OPTIONAL, 'Max Years', 0)
		);

	}

	public function schedule(Schedulable $scheduler)
    {
		return $scheduler->daily();
    }

}
