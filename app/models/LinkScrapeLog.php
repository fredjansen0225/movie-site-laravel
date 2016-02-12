<?php

class LinkScrapeLog extends Entity {

	/**
	 * Model table.
	 * 
	 * @var string
	 */
	public $table = 'link_scrape_logs';

    public function linkScrape()
    {
        return $this->belongsTo('LinkScrape');
    }

}