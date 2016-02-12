<?php namespace Lib\Services\Validation;

class GroupValidator extends AbstractValidator
{
	public $rules = array(

			'name'          => 'required|alpha_num|min:2|max:25|unique:groups',
			'super'	        => 'required|max:1|numeric',
			'titles_edit'   => 'required|max:1|numeric',
			'titles_create' => 'required|max:1|numeric',
			'titles_delete' => 'required|max:1|numeric',
			'custom'		=> 'regex:/.+?:[0-1]/',
		
		);

	protected $messages = array(

			'regex' => 'valid format is group name colon(:) and 1 or 0'
		);
}