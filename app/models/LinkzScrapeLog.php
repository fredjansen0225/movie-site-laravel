<?php

class LinkzScrapeLog extends Entity {

	/**
	 * Model table.
	 * 
	 * @var string
	 */
	public $table = 'linkz_scrape_logs';

    public function linkScrape()
    {
        return $this->belongsTo('LinkzScrape');
    }

}