<?php

class ImdbScrapeLog extends Eloquent
{

    public function imdbTitleScrape()
    {
        return $this->belongsTo('ImdbTitleScrape');
    }

}

