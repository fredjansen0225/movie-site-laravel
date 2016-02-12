<?php

class Episode extends Eloquent
{
	public function title()
    {
       return $this->belongsTo('Title');
    }

    public function season()
    {
       return $this->belongsTo('Season');
    }

     /**
     * Returns default image if title doesnt have poster.
     * 
     * @param  string $value 
     * @return string
     */
    public function getPosterAttribute($value)
    {
        if ( ! $value)
        {
            return url('assets/images/imdbnoimage.jpg');
        }

        if ( ! str_contains($value, 'http'))
        {
            return url($value);
        }

        return $value;
    }

    public function links()
    {
        return $this->hasMany('Link', 'episode', 'episode_number')->where('season', '=', $this->season_number)->where('title_id', '=', $this->title_id);
    }
}