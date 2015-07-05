<?php 

/**
* Maintenance class
*/

class Maintenance
{
	public static function check()
	{
		if(!Config::get("maintenance.activate") || in_array(self::thisUrl(), Config::get("maintenance.outRoutes")))
			return true;
		else return false;
	}

	public static function show()
	{
		$msg=Config::get("maintenance.msg");
		$bg_color=Config::get("maintenance.bg");
		//include 'View.php';
		View::make('maintenance.view',['msg' => $msg , 'bg_color' => $bg_color ]);
	}

	protected static function thisUrl()
	{
		$url=isset($_GET['url'])?$_GET['url']:"/";
		return $url;
	}
}