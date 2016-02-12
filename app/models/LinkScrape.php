<?php

class LinkScrape extends Entity {

	/**
	 * Model table.
	 * 
	 * @var string
	 */
	public $table = 'link_scrapes';

    /**
     * One to many relationship with title model.
     * 
     * @return Relationship
     */
    public function link()
    {
        return $this->belongsTo('Link');
    }

    public function title()
    {
        return $this->belongsTo('Title');
    }


}