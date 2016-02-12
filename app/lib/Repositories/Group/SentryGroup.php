<?php namespace Lib\Repositories\Group;

use Carbon\Carbon;
use DB, Groups, Sentry, Event;

class SentryGroup implements GroupRepositoryInterface
{
	/**
	 * Group model instance.
	 * 
	 * @var Group
	 */
	private $group;

	public function __construct(Groups $group)
	{
		$this->group = $group;	
	}

	/**
	 * Creates a new group.
	 * 
	 * @param  array  $input
	 * @return void
	 */
	public function create(array $input)
	{
		//create base group array
		$newGroup = array(
		      'name'        => $input['name'],
		      'permissions' => array(
		      		'super' => $input['super'],
		      		'titles.edit' => $input['titles_edit'],
		      		'titles.create' => $input['titles_create'],
		      		'titles.delete' => $input['titles_delete']
		      		)
		  		);

		//check if we got any custom permissions, if we do
		//parse them out and add to base group array
		if ($input['custom'])
		{
			$custom = $this->parseCustom($input['custom']);

			foreach ($custom as $k => $v)
			{
				$newGroup['permissions'][$k] = $v;
			}
		}

		Sentry::createGroup($newGroup);

		Event::fire('Groups.Created', array($input, Carbon::now(), 'Created'));
	}

	/**
	 * Deletes specified group.
	 * 
	 * @param  array $input
	 * @return void
	 */
	public function delete(array $input)
	{
		$this->group->where('name', '=', $input['name'])->forceDelete();

		Event::fire('Groups.Deleted', array($input, Carbon::now(), 'Deleted'));
	}

	/**
	 * Clears group table activity log.
	 * 
	 * @return void
	 */
	public function clearLog()
	{
		DB::table('group_activity')->truncate();

		Event::fire('Groups.logCleared', array(null, Carbon::now(), 'Log Cleared'));
	}

	/**
	 * Parsers custom permissions string for insert.
	 * 
	 * @param  string $perm
	 * @return array
	 */
	private function parseCustom($perm)
	{
		if (strpos($perm, ','))
		{
			$permissions = explode(',', $perm);

			foreach ($permissions as $v)
			{
				$perms = explode(':', $v);

				$compiled[trim($perms[0])] = trim($perms[1]);
			}

			return $compiled;
		}
		else
		{
			$perms = explode(':', $perm);

			return array($perms[0] => $perms[1]);
		}
	}	
}