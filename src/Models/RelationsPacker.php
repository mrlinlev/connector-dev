<?php

namespace Leveon\Connector\Models;

class RelationsPacker extends APacker{
	
	#prop newRelations vgS aprot
	protected $newRelations = [];
	#prop oldRelations vgS aprot
	protected $oldRelations = [];
	
	public function add($relation){
		$this->newRelations[] = $relation;
		return $this;
	}
	
	public function delete($relation){
		$this->oldRelations[] = $relation;
		return $this;
	}
	
	public function toJSON($rules = []){
		if(in_array('new', $rules)){
			return $this->pack($this->newRelations, Relation::class);
		}
		if(in_array('old', $rules)){
			return $this->pack($this->oldRelations, Relation::class);
		}
		throw new \Exception("Wrong args: ".implode(', ', $rules));
	}
	
	
	#gen - begin
	public function getNewRelations(){ return $this->newRelations; }
	public function getOldRelations(){ return $this->oldRelations; }

	#gen - end
}