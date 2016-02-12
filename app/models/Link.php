<?php

class Link extends Entity {

	/**
	 * Model table.
	 * 
	 * @var string
	 */
	public $table = 'links';

    /**
     * Not fillable by mass asignment.
     * 
     * @var array
     */
    protected $guarded = array('id');

    protected $softDelete = true;


    /**
     * One to many relationship with title model.
     * 
     * @return Relationship
     */
    public function title()
    {
        return $this->belongsTo('Title');
    }

    public function linkReports()
    {
        return $this->hasMany('LinkReport');
    }

    public function upvotes()
    {
        return $this->hasMany('LinkReport')->where('link_reports.value', '=', 1);
    }

    public function downvotes()
    {
        return $this->hasMany('LinkReport')->where('link_reports.value', '=', -1);
    }

    public function flags()
    {
        return $this->hasMany('LinkReport')->where('link_reports.value', '=', -2);
    }

    public function linkClicks()
    {
        return $this->hasMany('LinkClick');
    }

    public function linkScrapes()
    {
        return $this->hasMany('LinkScrape');
    }

    public function getWrappedUrlAttribute()
    {
        return URL::to('link/open/'.$this->id);
    }

    public function getDomainAttribute()
    {
        $url = parse_url($this->url);
        return array_get($url, 'host', 'External Site');
    }

    public function userLinkReport()
    {
        return $this->hasOne('LinkReport')->where(function($query) {
            $user = Helpers::loggedInUser();
    
            $query->where('ip_address', '=', Request::getClientIp());
            $query->orWhere('user_id', '=', $user ? $user->id : 0);
        });
    }

}