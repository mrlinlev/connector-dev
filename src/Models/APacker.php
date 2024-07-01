<?php

namespace Leveon\Connector\Models;

abstract class APacker{
	
	public static function New(): static
    {
		return new static();
	}
	
	abstract function toJSON($rules = []);
	
	protected function pack($list, $class): ?array
    {
		if(count($list)===0) return null;
		$compress = [];
		$left = $class::$compressable;
		foreach($left as $field){
			$compress[$field] = [];
		}
		$final = $class::$final;
		if($final!==null) $left[] = $final;
		$result = [];
		$cs = [];
		foreach($compress as $field => &$uniqList){
			foreach($list as $item){
				$value = $item->val($field);
				if(!in_array($value, $uniqList, true)) $uniqList[] = $value;
			}
		}
		foreach($compress as $key => $value){
			$count = count($value);
			if($count===1){
				$result[$key] = $value[0];
				$left = array_diff($left, [$key]);
			}elseif($count>0){
				$cs[$key] = $count;
			}
		}
		asort($cs, SORT_NUMERIC);
		if(count($cs)>0){
			$key = array_keys($cs)[0];
			$left = array_values(array_diff($left, [$key]));
			$o = [];
			if(count($left)>1){
				foreach($list as $price){
					if(!isset($o[$price->val($key)])) $o[$price->val($key)] = [];
					$o[$price->val($key)][] = $price->toJSON($left);
				}
				$result["${$key}"] = $o;
			}elseif(count($left)===1){
				$vkey = $left[0];
				foreach($list as $price){
					if(!isset($compress[$vkey])){
						$o[$price->val($key)] = $price->val($vkey);
					}else{
						if(!isset($o[$price->val($key)])){
							$o[$price->val($key)] = [$vkey => [$price->val($vkey)]];
						}else{
							$o[$price->val($key)][$vkey][] = $price->val($vkey);
						}
					}
				}
				$result["${$key}"] = $o;
			}else{
				foreach($list as $price){
					$o[] = $price->val($key);
				}
				$result[$key] = $o;
			}
		}else{
			if($final!==null)
				$result[$final] = $list[0]->val($final);
		}
		return $result;
	}
	
}