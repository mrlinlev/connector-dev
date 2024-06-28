<?php

namespace Leveon\Connector\Models;

use Leveon\Connector\Exceptions\CodeException;

abstract class AModel{

    protected static array $valueableList = [];
    protected static array $lists = [];
    protected static array $entitiesList = [];
	
	public function __construct($info = null){
		if($info!==null){
			$this->loadPresentedFields($info, static::$valueableList);
			foreach(static::$lists as $field => $class){
				$val = self::GetField($info, $field);
				if($val!==null)
					$this->{$field} = $class::LoadAssocList($val);
			}
			foreach(static::$entitiesList as $field => $class){
				$val = self::GetField($info, $field);
				if($val!==null)
					$this->{$field} = new $class($val);
			}
		}
	}

	public static function New($info = null): static
    {
		return new static($info);
	}

    /**
     * @param $key
     * @return mixed
     * @throws CodeException
     */
    public function val($key): mixed
    {
		if(isset($this->{$key})) return $this->{$key};
		throw new CodeException("Unknown field {$key} in class ".static::class);
	}

	protected function fieldsToJson($fields): array
    {
		$result = [];
		foreach($fields as $field){
			if($this->{$field} instanceof AModel)
				$result[$field] = $this->{$field}->toJSON();
			else
				$result[$field] = $this->{$field};
		}
		return $result;
	}

	public function toJSON($rules = []): object
    {
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

	public static function LoadList($array, $pre = [], $post = []): array
    {
		return array_map(function($p)use($pre, $post){
			$args = [...$pre, $p, ...$post];
			return new static(...$args); 
		}, $array);
	}
	
	public static function LoadAssocList($array, $pre = [], $post = []): array
    {
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
	
	protected function loadField($info, $field, $default = null): static
    {
		if(is_array($info)){
			$this->{$field} = $info[$field] ?? $default;
		}else{
			$this->{$field} = $info->{$field} ?? $default;
		}
		return $this;
	}
	
	protected function loadPresentedField($info, $field): static
    {
		if(is_array($info) && isset($info[$field])){
			$this->{$field} = $info[$field];
		}elseif(isset($info->{$field})){
			$this->{$field} = $info->{$field};
		}
		return $this;
	}
	
	protected static function GetField($info, $field, $default = null){
		if(is_array($info)){
			return $info[$field] ?? $default;
		}else{
			return $info->{$field} ?? $default;
		}
	}
	
	protected function loadFields($info, $fields, $default = null): static
    {
		foreach($fields as $field)
			$this->loadField($info, $field, $default);
		return $this;
	}
	
	protected function loadPresentedFields($info, $fields): static
    {
		foreach($fields as $field)
			$this->loadPresentedField($info, $field);
		return $this;
	}
	
}