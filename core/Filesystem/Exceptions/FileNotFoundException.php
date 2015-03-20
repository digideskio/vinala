<?php 

namespace Fiesta\Filesystem;

/**
* File not fount exception
*/
class FileNotFoundException extends \Exception
{
	protected $message;   // exception message

	function __construct($path) 
	{
		$this->message="File does not existe in ($path)";
	}
}

