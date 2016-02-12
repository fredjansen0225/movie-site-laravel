<?php 

class Slide extends Entity {

	/**
	 * Table name in database.
	 * 
	 * @var string
	 */
	protected $table = 'slides';

	/**
	 * Disable automatic timestamps.
	 * 
	 * @var boolean
	 */
	public $timestamps = false;

	/**
     * Make image url absolute if not already.
     *
     * @param string value
     * @return string
     */
    public function getImageAttribute($img)
    {
    	if ( ! str_contains($img, 'http'))
    	{
    		return url($img);
    	}

        return $img;
    }

}