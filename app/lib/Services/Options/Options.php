<?php namespace Lib\Services\Options;

class Options
{
	/**
	 * Site Preferences.
	 * 
	 * @var array
	 */
	public $options;

	/**
	 * Instantiate new options instance.
	 * 
	 * @param mixed $options
	 */
	public function __construct($options = null)
	{
		if ($options)
		{
			$this->options = $options;
		}

		//set some defaults if nothing passed
		else
		{
			$this->options['data_provider'] = 'imdb';
			$this->options['search_provider'] = 'imdb';
		}
	}

	/**
	 * Return data provider.
	 * 
	 * @return string
	 */
	public function getDataProvider()
	{
		return isset($this->options['data_provider']) ? $this->options['data_provider'] : 'imdb';
	}

	/**
	 * Return news provider.
	 * 
	 * @return string
	 */
	public function getNewsProvider()
	{
		return isset($this->options['news_provider']) ? $this->options['news_provider'] : 'firstshowing';
	}

	/**
	 * Return data provider.
	 * 
	 * @return string
	 */
	public function getContactEmail()
	{
		if (isset($this->options['contact_us_email']))
		{
			return $this->options['contact_us_email'];
		}
	}

	/**
	 * Return data provider.
	 * 
	 * @return string
	 */
	public function getLogo()
	{
		if (isset($this->options['logo']) && strlen($this->options['logo']) > 3)
		{
			return $this->options['logo'];
		}

		return url('assets/images/logo.png');
	}

	/**
	 * Return genres.
	 * 
	 * @return string
	 */
	public function getGenres()
	{
		if (isset($this->options['genres']))
		{
			return explode('|', $this->options['genres']);
		}

		return array('Action', 'Drama', 'Horror', 'Animation');
	}

	/**
	 * Return search provider.
	 * 
	 * @return string
	 */
	public function getSearchProvider()
	{
		return isset($this->options['search_provider']) ? $this->options['search_provider'] : 'imdb';
	}

	/**
	 * Return tmdb api key.
	 * 
	 * @return mixed
	 */
	public function getTmdbKey()
	{
		if (isset($this->options['tmdb_api_key']) && $this->options['tmdb_api_key'])
		{
			return $this->options['tmdb_api_key'];
		}
	}

	/**
	 * Returns all options.
	 * 
	 * @return array
	 */
	public function getAll()
	{
		return $this->options;
	}

	/**
	 * Returns amazon affiliate id
	 * 
	 * @return mixed
	 */
	public function getAmazonId()
	{
		if ( isset($this->options['amazon_id']) && $this->options['amazon_id'])
		{
			return $this->options['amazon_id'];
		}

		return null;
	}

	/**
	 * Returns uri separator.
	 * 
	 * @return string
	 */
	public function getUriSeparator()
	{
		if ( isset($this->options['uri_separator']) && $this->options['uri_separator'])
		{
			return $this->options['uri_separator'];
		}

		return '-';
	}

	/**
	 * Returns uri case.
	 * 
	 * @return string
	 */
	public function getUriCase()
	{
		if ( isset($this->options['uri_case']) && $this->options['uri_case'])
		{
			return $this->options['uri_case'];
		}

		return '';
	}

	/**
	 * Returns disqus shortname.
	 * 
	 * @return mixed
	 */
	public function getDisqusShortname()
	{
		if ( isset($this->options['disqus_short_name']) && $this->options['disqus_short_name'])
		{
			return $this->options['disqus_short_name'];
		}

		return null;
	}

	/**
	 * Returns facebook page url.
	 * 
	 * @return mixed
	 */
	public function getFb()
	{
		if ( isset($this->options['fb_url']) && $this->options['fb_url'])
		{
			return $this->options['fb_url'];
		}

		return null;
	}

	/**
	 * Returns twitter page url.
	 * 
	 * @return mixed
	 */
	public function getTw()
	{
		if ( isset($this->options['tw_url']) && $this->options['tw_url'])
		{
			return $this->options['tw_url'];
		}

		return null;
	}

	/**
	 * Returns google page url.
	 * 
	 * @return mixed
	 */
	public function getGoogle()
	{
		if ( isset($this->options['google_url']) && $this->options['google_url'])
		{
			return $this->options['google_url'];
		}

		return null;
	}

	/**
	 * Returns youtube page url.
	 * 
	 * @return mixed
	 */
	public function getYoutube()
	{
		if ( isset($this->options['youtube_url']) && $this->options['youtube_url'])
		{
			return $this->options['youtube_url'];
		}

		return null;
	}

	/**
	 * Returns whether use should be required to activate
	 * his account via email or not.
	 * 
	 * @return boolean
	 */
	public function requireUserActivation()
	{
		if ( array_key_exists('require_act', $this->options) )
		{
			return (int) $this->options['require_act'];
		}

		return true;
	}

	/**
	 * Returns whether or not to enable buy now button
	 * in title page.
	 * 
	 * @return boolean
	 */
	public function enableBuyNow()
	{
		if ( array_key_exists('enable_buy_now', $this->options) )
		{
			return (boolean) $this->options['enable_buy_now'];
		}

		return true;
	}

	/**
	 * Returns which player to use for playing trailers.
	 * 
	 * @return string
	 */
	public function trailersPlayer()
	{
		if (isset($this->options['video_player']))
		{
			return $this->options['video_player'];
		}

		return 'default';
	}


	/**
	 * Returns whether or not to save images locally from tmdb.
	 * 
	 * @return boolean
	 */
	public function saveTmdbImages()
	{
		if ( array_key_exists('save_tmdb', $this->options) )
		{
			return (int) $this->options['save_tmdb'];
		}

		return false;
	}

	/**
	 * Get TMDB api language code.
	 * 
	 * @param  string $color
	 * @return mixed
	 */
	public function getTmdbLang()
	{
		if (isset($this->options['tmdb_language']) && $this->options['tmdb_language'])
		{
			return $this->options['tmdb_language'];
		}

		return 'en';
	}

	/**
	 * Returns footer ad.
	 * 
	 * @return string
	 */
	public function getFooterAd()
	{
		if (isset($this->options['ad_footer_all']))
		{
			return $this->options['ad_footer_all'];
		}
	}

	/**
	 * Returns google analytics code.
	 * 
	 * @return string
	 */
	public function getAnalytics()
	{
		if (isset($this->options['analytics']))
		{
			return $this->options['analytics'];
		}
	}

	/**
	 * Returns title jumbo ad.
	 * 
	 * @return string
	 */
	public function getTitleJumboAd()
	{
		if (isset($this->options['ad_title_jumbo']))
		{
			return $this->options['ad_title_jumbo'];
		}
	}

	/**
	 * Returns home jumbo ad.
	 * 
	 * @return string
	 */
	public function getHomeJumboAd()
	{
		if (isset($this->options['ad_home_jumbo']))
		{
			return $this->options['ad_home_jumbo'];
		}
	}

	/**
	 * Returns home news ad.
	 * 
	 * @return string
	 */
	public function getHomeNewsAd()
	{
		if (isset($this->options['ad_home_news']))
		{
			return $this->options['ad_home_news'];
		}
	}
}