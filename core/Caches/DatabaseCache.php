<?php 

namespace Fiesta\Core\Caches;

/**
* FileCache class
*/
class DatabaseCache
{

	private $old_server;

	private function establish()
	{
		if(!is_null($data=$this->DatabaseInfo()))
		{
			$data=$this->DatabaseInfo();
			$this->old_server=\Fiesta\Core\Database\Database::$serverData;
			\Fiesta\Core\Database\Database::setNewServer($data['host'],$data['username'],$data['password'],$data['database']);
		}
		else
		{
			$this->old_server=\Fiesta\Core\Database\Database::$serverData;
		}
	}

	private function DatabaseTableName()
	{
		return \Fiesta\Core\Config\Config::get('cache.options')["database"]["table"];
	}

	private function DatabaseInfo()
	{
		return \Fiesta\Core\Config\Config::get("cache.options")['database']['database'];
	}

	private function createCacheTable()
	{
		\Fiesta\Core\Database\Schema::create($this->DatabaseTableName(),function($tab)
			{
				$tab->inc("id");
				$tab->string("name");
				$tab->string("val");
				$tab->long("life");
				$tab->unique("cacheunique",["name"]);
			});
	}

	private function back()
	{
		return \Fiesta\Core\Database\Database::setNewServer($this->old_server['host'],$this->old_server['username'],$this->old_server['password'],$this->old_server['database']);
	}

	public function put($key, $value, $minutes)
	{
		$value = $this->packing($value);
		$time = $this->expiration($minutes);
		$name = $this->hash($key);
		//$name = $key;

		//
		$this->establish();
		//
		if( ! \Fiesta\Core\Database\Schema::existe($this->DatabaseTableName(),\Fiesta\Core\Database\Database::$serverData['database']))
		{
			$this->createCacheTable();
		}
		$data=[["name" => $name ,"val" => $value ,"life" => $time ]];
		if( ! $this->exists($key))
			\Fiesta\Core\Database\Schema::table($this->DatabaseTableName())->insert($data);
		else 
			\Fiesta\Core\Database\Schema::table($this->DatabaseTableName())->update("name='".$name."'",$data);
		//
		$this->back();
	}

	public function get($key,$default=null)
	{
		$this->establish();
		//
		if($this->exists($key))
		{
			$key = $this->hash($key);
			$value=$this->read($key);
			//
			$parts = $this->unpacking($value[0]['val']);
			//
			return $parts;
		}
		else 
		{
			if(is_null($default)) return false;
			else return $default;
		}
		//
		$this->back();
	}

	protected function expiration($minutes)
	{
		if ($minutes === 0) return 9999999999;

		return time() + ($minutes * 60);
	}

	protected function packing($value)
	{
		return serialize($value);
	}

	protected function unpacking($value)
	{
		return unserialize($value);
	}

	protected function hash($value)
	{
		return md5($value.\Fiesta\Core\Config\Config::get("security.key1").md5($value));
	}

	protected function forget($key)
	{
		//$sql="delete from fiestacache where name='".$key."'";
		$key = $this->hash($key);
		return \Fiesta\Core\Database\Database::exec("delete from ".$this->DatabaseTableName()." where name='".$key."'");
	}

	private function exist($key)
	{

		return (\Fiesta\Core\Database\Database::countS("select * from ".$this->DatabaseTableName()." where name='$key'")>0);
	}

	private function read($key)
	{
		return \Fiesta\Core\Database\Database::read("select * from ".$this->DatabaseTableName()." where name='$key'");
		// return (\Database::countS("select * from fiestacache where name='$key'")>0);
	}

	public function exists($key)
	{
		$key2 = $this->hash($key);
		//
		if($this->exist($key2))
		{
			if(time()>$this->getExpiration($key)) 
			{
				$this->forget($key);
				return false;
			}
			else return true;
		}
		else return false;
	}

	protected function getExpiration($key)
	{
		$key2 = $this->hash($key);
		//
		if($this->exist($key2))
		{
			$content=$this->read($key2);
			$content=$content[0];
			return $content['life'];
		}
		else return false;
	}

	public function forever($key,$value)
	{
		return $this->put($key,$value,0);
	}

	public function getAll()
	{
		return \Fiesta\Core\Database\Database::read('select * from '.$this->DatabaseTableName());
	}

	public function clearOld()
	{
		$all=$this->getAll();
		//
		foreach ($all as $rows) {
			$life=$rows['life'];
			if(time()>$life) \Fiesta\Core\Database\Schema::table($this->DatabaseTableName())->delete("name='".$rows['name']."'");
		}
		return true;
	}

	public function prolongation($key,$minutes)
	{
		if($this->exists($key))
		{
			$key2=$this->hash($key);
			$parts=$this->read($key2);
			//
			$one=$parts[0];
			//
			//$one["life"]=$one["life"] + ($minutes * 60);
			$one["val"]=$this->unpacking($one["val"]);
			//
			$this->put($key ,$one["val"], $minutes);
		} else return false;
	}

	public function pull($key)
	{
		if($this->exists($key))
		{
			$value=$this->get($key);
			$this->forget($key);
			return $value;
		}
		else return false;
	}



}