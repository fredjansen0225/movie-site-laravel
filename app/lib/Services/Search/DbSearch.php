<?php namespace Lib\Services\Search;

class DbSearch implements SearchProviderInterface
{
	/**
	 * Searches for a title by query.
	 *
	 * @param  string $query
	 * @return array
	 */
	public function byQuery($query)
	{
	    return \Title::whereTitleLike(preg_replace("/[^A-Za-z0-9]/i", '%', $query))->get();
	}
}