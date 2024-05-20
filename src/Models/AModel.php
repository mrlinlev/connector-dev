<?php

namespace Leveon\Connector\Models;

abstract class AModel{
	
	protected static $defaults = [];
	protected static $valueableList = [];
	protected static $lists = [];
	protected static $entitiesList = [];	
	
	public function __construct($info = null){
		if($info!==null){
			$this->loadPresentedFields($info, static::$valueableList);
			foreach(static::$lists as $field => $class){
				$val = self::GetField($info, $field);
				if($val!==null)
					$this->{$field} = $class::LoadAssocList($val, [$this->preferences]);
			}
			foreach(static::$entitiesList as $field => $class){
				$val = self::GetField($info, $field);
				if($val!==null)
					$this->{$field} = new $class($this->preferences, $val);
			}
		}
	}

	public static function New($info = null){
		return new static($info = null);
	}

	public function val($key){
		if(isset($this->{$key})) return $this->{$key};
		throw new \Exception("Unknown field {$key} in class ".static::class);
	}

	protected function fieldsToJson($fields){
		$result = [];
		foreach($fields as $field){
			if($this->{$field} instanceof AModel)
				$result[$field] = $this->{$field}->toJSON();
			else
				$result[$field] = $this->{$field};
		}
		return $result;
	}

	public function toJSON($rules = []){
		$pack = [];
		foreach(static::$valueableList as $item){
			if($this->{$item}!==null) $pack[] = $item;
		}
		$result = $this->fieldsToJson($pack);
		foreach(static::$entitiesList as $field => $class){
			if($this->{$field}!==null)
				$result[$field] = $this->{$field}->toJSON();
		}
		foreach(static::$lists as $field => $class){
			if($this->{$field}!==null && count($this->{$field})>0)
				$result[$field] = self::ListToJSON($this->{$field});
		}
		return (object)$result;
	}

	public static function LoadList($array, $pre = [], $post = []){
		return array_map(function($p)use($pre, $post){
			$args = [...$pre, $p, ...$post];
			return new static(...$args); 
		}, $array);
	}
	
	public static function LoadAssocList($array, $pre = [], $post = []){
	  $result = [];
	  foreach ($array as $key=>$p){
	    $args = [...$pre, $p, ...$post];
	    $result[$key] = new static(...$args);
	  }
		return $result;
	}
	
	
	public static function ListToJSON($list){
		return array_map(function($p){ return $p->toJSON(); }, $list); 
	}
	
	protected function loadField($info, $field, $default = null){
		if(is_array($info)){
			$this->{$field} = isset($info[$field])? $info[$field]: $default;
		}else{
			$this->{$field} = isset($info->{$field})? $info->{$field}: $default;
		}
		return $this;
	}
	
	protected function loadPresentedField($info, $field){
		if(is_array($info) && isset($info[$field])){
			$this->{$field} = $info[$field];
		}elseif(isset($info->{$field})){
			$this->{$field} = $info->{$field};
		}
		return $this;
	}
	
	protected static function GetField($info, $field, $default = null){
		if(is_array($info)){
			return isset($info[$field])? $info[$field]: $default;
		}else{
			return isset($info->{$field})? $info->{$field}: $default;
		}
	}
	
	protected function loadFields($info, $fields, $default = null){
		foreach($fields as $field)
			$this->loadField($info, $field, $default);
		return $this;
	}
	
	protected function loadPresentedFields($info, $fields){
		foreach($fields as $field)
			$this->loadPresentedField($info, $field);
		return $this;
	}
	
}