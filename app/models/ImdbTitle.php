<?php

class ImdbTitle extends Eloquent
{

	protected $fillable = array('imdb_id', 'original_title', 'type','poster', 'year', 'plot', 'genre', 'imdb_rating', 'runtime', 'imdb_votes_num');

	public function title()
	{
		return $this->hasOne('Title', 'imdb_id', 'imdb_id');
	}
    public function imdbScrapes()
    {
    	return $this->hasMany('ImdbScrape');
    }
	public function getAsImdbSearchData()
	{
		$map = array(
			'title_name' => 'title',
			'imdb_id' => 'imdb_id',
			'original_title' =>  'original_title',
			'type'=>'type',
			'poster' => 'poster',
			'year' => 'year',
			'plot' => 'plot',
			'genre' => 'genre',
			'imdb_rating' => 'imdb_rating',
			'runtime' => 'runtime',
			'imdb_votes_num' => 'imdb_votes_num');

		$data = array();
		foreach($map as $from => $to)
		{
			$data[$to]  = $this->$from;
		}

		return $data;
	}
}

