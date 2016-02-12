<?php namespace Lib\Titles;

use Carbon\Carbon;
use Lib\Services\Db\Writer;
use Title, Season, Episode, Helpers, App;
use Lib\Repositories\Data\DataRepositoryInterface as Provider;

class SeasonRepository
{
    /**
     * Season model instance.
     * 
     * @var Season
     */
    private $season;

    /**
     * dbWriter instance.
     * 
     * @var Lib\Services\Db\Writer
     */
    private $dbWriter;

    /**
     * Episode model instance.
     * 
     * @var Episode
     */
    private $episode;

    /**
     * Title model instance.
     * 
     * @var Title
     */
    private $title;

    /**
     * Data provider instance.
     * 
     * @var Lib\Repositories\Data\DataProviderInterface;
     */
    private $provider;

    public function __construct(Season $season, Writer $dbWriter, Episode $episode, Title $title, Provider $provider)
    {
        $this->season   = $season;
        $this->dbWriter = $dbWriter;
        $this->episode  = $episode;
        $this->title    = $title;
        $this->provider = $provider;
    }

    /**
     * Prepares single season for displaying.
     * 
     * @param  Title  $title
     * @param  string $num
     * @return Title
     */
    public function prepareSingle(Title $title, $num)
    {
        //if provider tmdb we'll defer the handling
        //to tmdb method because the optimal way of fetching seasons
        //differs too much between providers
        if ($this->provider->name == 'tmdb' && $title->tmdb_id)
        {
            $first = $title->season->first();

            if ( ! $first) App::abort(404);

            //if first season is fully scraped and has tmdb id
            //means all seasons are fully scraped, so we'll just return
            if ($first->fully_scraped && $first->title_tmdb_id && Carbon::parse($first->updated_at)->addDays(7) >= Carbon::now())
            {
                return $title;
            }

            $this->fetchFromTmdb($title);

            return $title->with('season.episode')->findOrFail($title->id);
        }

        return $this->handleSingleLoading($title, $num);
    }

    /**
     * Handles single season loading if provider is not tmdb.
     * 
     * @param  DataProviderInterface $provider
     * @param  Title $title
     * @param  int/string $num
     * @return Title
     */
    public function handleSingleLoading($title, $num)
    {
        $season = Helpers::extractSeason($title, $num);

        if ($this->provider->name == 'db' || ! $title->allow_update)
        {
            return $title;
        }

        if ($season->fully_scraped && Carbon::parse($season->updated_at)->addDays(7) >= Carbon::now())
        {
            return $title;
        }

        if ($this->provider->name == 'tmdb')
        {
            $provider = App::make('Lib\Repositories\Data\ImdbData');

            $episodes =  $provider->getSingleSeason($title, $num);
        }
        else
        {
            //get all episodes for specified season
            $episodes = $this->provider->getSingleSeason($title, $num);
        }
                
        //insert episodes and change fully_scraped flag to 1
        $this->dbWriter->CompileBatchInsert('episodes', $episodes)->save();
        $this->dbWriter->CompileInsert('seasons', array('id' => $season->id, 'fully_scraped' => 1, 'updated_at' => Carbon::now()))->save();

        return $title->with('season.episode')->findOrFail($title->id);
    }

    /**
     * Fetches all seasons and episodes for given series.
     * 
     * @param  Title  $title    
     * @param  TmdbParser $provider
     * @return void
     */
    private function fetchFromTmdb(Title $title)
    {
        $seasons = $this->provider->getFullAllSeasons($title);
        $this->dbWriter->saveFullAllSeasons($seasons);
    }

    /**
     * Finds season by title id and season number.
     * 
     * @param  string $title
     * @param  int/string $num
     * @return Season/404
     */
    public function findById($id, $num)
    {
        return $this->title->find($id)->season()->whereNumber($num)->firstOrFail();
    }

    /**
     * Handles new season creation from input.
     * 
     * @param  array $input
     * @return Season
     */
    public function create(array $input)
    {
        $temp = str_random(10);
        $input['temp_id'] = $temp;

        $this->dbWriter->CompileInsert('seasons', $input)->save();

        return $this->season->where('temp_id', $temp)->limit(1)->get(array('id'))->first();
    }

    /**
     * Handles season deletion.
     * 
     * @param  int/string $series series id
     * @param  int/string $season season id
     * @return void
     */
    public function delete($series, $season)
    {
        $this->episode->where('season_id', '=', $season)->delete();
        $this->season->destroy($season);
    }

    /**
     * Fetches series with all seasons and episodes.
     * 
     * @param  string $title
     * @return Title
     */
    public function withSeasonsEpisodes($title)
    {
        $id = Helpers::extractId($title);

        return $this->title->with('season.episode')
                           ->whereType('series')
                           ->findOrFail($id);
    }
}