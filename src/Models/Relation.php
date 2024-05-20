<?php

namespace Leveon\Connector\Models;

class Relation extends AModel{
	
	#prop relation vgs aprot
	protected $relation;
	#prop source vgs aprot
	protected $source;
	#prop target vgs aprot
	protected $target;
	
	public static $compressable = [
		'relation',
		'source',
		'target',
	];
	
	public static $final = null;
	
	protected static $valueableList = [
		'relation',
		'source',
		'target',
	];
	
	public function toJSON($rules = []){
		$result = [];
		if(in_array('relation', $rules) && $this->relation!==null) $result['relation'] = $this->relation;
		if(in_array('source', $rules) && $this->source!==null) $result['source'] = $this->source;
		if(in_array('target', $rules) && $this->target!==null) $result['target'] = $this->target;
		return $result;
	}
	
	#gen - begin
	public function getRelation(){ return $this->relation; }
	public function setRelation($relation){ $this->relation = $relation; return $this; }
	public function getSource(){ return $this->source; }
	public function setSource($source){ $this->source = $source; return $this; }
	public function getTarget(){ return $this->target; }
	public function setTarget($target){ $this->target = $target; return $this; }
	#gen - end
}