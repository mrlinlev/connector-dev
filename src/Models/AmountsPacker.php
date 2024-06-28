<?php

namespace Leveon\Connector\Models;

use Leveon\Connector\Exceptions\CodeException;
use Leveon\Connector\Exceptions\DataException;

class AmountsPacker extends APacker{
	
	protected array $newAmounts = [];
	protected array $oldAmounts = [];

    /**
     * @param $value
     * @return $this
     * @throws DataException
     */
    public function add($value): static
    {
		if($value instanceof OfferAmount){
			$this->newAmounts[] = $value;
			return $this;
		}
		if($value instanceof DeleteOfferAmount){
			$this->oldAmounts[] = $value;
			return $this;
		}
		throw new DataException('Unknown value');
	}


    /**
     * @param array $rules
     * @return array|null
     * @throws CodeException
     */
    public function toJSON($rules = []): ?array
    {
		if(!isset($rules['delete'])){
			throw new CodeException('Delete not specified');
		}
        return !!$rules['delete']
            ? $this->pack($this->oldAmounts, DeleteOfferAmount::class)
            : $this->pack($this->newAmounts, OfferAmount::class);
	}
}