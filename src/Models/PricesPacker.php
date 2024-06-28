<?php

namespace Leveon\Connector\Models;

use Leveon\Connector\Exceptions\CodeException;
use Leveon\Connector\Exceptions\DataException;

class PricesPacker extends APacker{
	
	protected array $newPrices = [];
	protected array $oldPrices = [];

    /**
     * @param $value
     * @return $this
     * @throws DataException
     */
    public function add($value): static
    {
		if($value instanceof OfferPrice){
			$this->newPrices[] = $value;
			return $this;
		}
		if($value instanceof DeleteOfferPrice){
			$this->oldPrices[] = $value;
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
            ? $this->pack($this->oldPrices, DeleteOfferPrice::class)
            : $this->pack($this->newPrices, OfferPrice::class);
	}
}