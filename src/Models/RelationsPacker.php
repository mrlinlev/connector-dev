<?php

namespace Leveon\Connector\Models;

use Leveon\Connector\Exceptions\CodeException;

class RelationsPacker extends APacker{
	
	protected array $newRelations = [];
	protected array $oldRelations = [];
	
	public function add($relation): static
    {
		$this->newRelations[] = $relation;
		return $this;
	}
	
	public function delete($relation): static
    {
		$this->oldRelations[] = $relation;
		return $this;
	}

    /**
     * @param array $rules
     * @return array|null
     * @throws CodeException
     */
    public function toJSON($rules = []): ?array
    {
		if(in_array('new', $rules)){
			return $this->pack($this->newRelations, Relation::class);
		}
		if(in_array('old', $rules)){
			return $this->pack($this->oldRelations, Relation::class);
		}
		throw new CodeException("Wrong args: ".implode(', ', $rules));
	}
}