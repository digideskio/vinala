<?php 

include_once '../../../../../../core/Ini.php';

App::run(null,'../../../../../../',false,true,false);
//
$r=glob("../../../../../../app/schemas/*.php");
//int=0;

$pieces=array();
$pieces1=array();
$pieces2=array();
//
$time="";
$name="";
//
$f = array();
foreach ($r as $key) {
	//echo $key."\n";
	$pieces = explode("app/schemas/", $key);
	$pieces1 = explode("_", $pieces[1]);
	$time=$pieces1[0];
	$p=explode(".", $pieces1[1]);
	$name=$p[0];
	$f[]=$pieces1[0];
	$pieces2[]=$pieces[1];
	//
}

//echo "\n";
$mx=max($f);
//
$ind=0;$i=0;
//
foreach ($pieces2 as $value) {
	//echo $value."\n";
	
	if (strpos($value,$mx) !== false) $ind=$i;

	$i++;
}
$link=$r[$ind];
//

try {
	include_once $link;
	
	if(down())
	{
		
		if(Schema::existe(Config::get('database.migration')))
		{
			Database::exec("update ".Config::get('database.migration')." set status_schema='rolledback' where name_schema='".$name."' and date_schema='".$time."'");
		}
		echo "Schéma annulé";
		
	}
	else echo "Schema n'est pas annulé".Database::execErr();
} catch (Exception $e) {
	echo $e->getMessage();
}


