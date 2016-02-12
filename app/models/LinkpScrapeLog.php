<?php

class LinkpScrapeLog extends Entity {

	/**
	 * Model table.
	 * 
	 * @var string
	 */
	public $table = 'linkp_scrape_logs';

    public function linkpScrape()
    {
        return $this->belongsTo('LinkpScrape');
    }

}