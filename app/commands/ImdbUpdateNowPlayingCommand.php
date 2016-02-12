<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;


class ImdbUpdateNowPlayingCommand extends ScheduledCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'imdb:now-playing';

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
		$data = App::make('Lib\Repositories\Data\ImdbData');

		$this->info('Reset now playing...');

		Title::where('now_playing','>',0)->update(array('now_playing'=>0));


		$this->info('Load list from IMDB...');
		$titles = $data->getNowPlaying();

		foreach($titles as $imdbTitleData)
		{
			$title = Title::where('imdb_id', '=', $imdbTitleData['imdb_id'])->first();
			$title->now_playing = true;
			$title->save();
		}

		$this->info('Done.');

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
