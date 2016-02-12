<?php

class ImdbScrape extends Eloquent
{

    public function imdbTitle()
    {
        return $this->belongsTo('ImdbTitle');
    }

    public function imdbScrapeLogs()
    {
    	return $this->hasMany('ImdbScrapeLog');
    }

}

