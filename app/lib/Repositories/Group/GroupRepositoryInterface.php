<?php namespace Lib\Repositories\Group;

interface GroupRepositoryInterface
{
	/**
	 * Creates a new group.
	 * 
	 * @param  array  $input
	 * @return void
	 */
	public function create(array $input);

	/**
	 * Deletes specified group.
	 * 
	 * @param  array $input
	 * @return void
	 */
	public function delete(array $input);

	/**
	 * Clears group table activity log.
	 * 
	 * @return void
	 */
	public function clearLog();
}