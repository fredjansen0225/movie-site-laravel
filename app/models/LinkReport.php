<?php

class LinkReport extends Entity {

	/**
	 * Model table.
	 * 
	 * @var string
	 */
	public $table = 'link_reports';

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