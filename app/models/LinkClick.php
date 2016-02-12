<?php

class LinkClick extends Entity {

	/**
	 * Model table.
	 * 
	 * @var string
	 */
	public $table = 'link_clicks';

    /**
     * One to many relationship with title model.
     * 
     * @return Relationship
     */
    public function link()
    {
        return $this->belongsTo('Link');
    }


}