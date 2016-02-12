<?php namespace Lib\Lists;

use Lib\Repository;
use User, App, Sentry;
use Lib\Services\Db\Writer;

class ListRepository extends Repository
{

	/**
	 * DB writer instanace.
	 * 
	 * @var Lib\Services\Db\Writer
	 */
	protected $dbWriter;

	/**
	 * DB instance.
	 * 
	 * @var DB
	 */
	protected $db;

	public function __construct(Writer $dbWriter)
	{
		$this->dbWriter = $dbWriter;
		$this->db   = App::make('db');
	}

	/**
	 * Adds given title to given list of user.
	 *
	 * @param  array $input
	 * @return void
	 */
	public function add(array $input)
	{
		if ($user = Sentry::getUser())
		{
			$data = array('user_id' => $user->id,'title_id' => $input['title_id'], $input['list_name'] => 1);
		}

		$this->dbWriter->compileInsert('users_titles', $data)->save();
	}

	/**
	 * Removes given title from given list of user.
	 *
	 * @param  array $input 
	 * @return  String/Redirect
	 */
	public function remove(array $input)
	{
		if ($user = Sentry::getUser())
		{
			$this->db->table('users_titles')
				 ->where('title_id', $input['title_id'])
				 ->where('user_id', $user->id)
				 ->where($input['list_name'], 1)
				 ->delete();
		}
	}
}