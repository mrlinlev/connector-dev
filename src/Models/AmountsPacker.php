<?php

namespace Leveon\Connector\Models;

use Exception;

class AmountsPacker extends APacker{
	
	#prop newProductAmounts vgS aprot
	protected $newProductAmounts = [];
	#prop oldProductAmounts vgS aprot
	protected $oldProductAmounts = [];
	#prop newOfferAmounts vgS aprot
	protected $newOfferAmounts = [];
	#prop oldOfferAmounts vgS aprot
	protected $oldOfferAmounts = [];

    /**
     * @param $value
     * @return $this
     * @throws Exception
     */
    public function add($value){
		if($value instanceof ProductAmount){
			$this->newProductAmounts[] = $value;
			return $this;
		}
		if($value instanceof OfferAmount){
			$this->newOfferAmounts[] = $value;
			return $this;
		}
		if($value instanceof DeleteProductAmount){
			$this->oldProductAmounts[] = $value;
			return $this;
		}
		if($value instanceof DeleteOfferAmount){
			$this->oldOfferAmounts[] = $value;
			return $this;
		}
		throw new Exception('Unknown value');
	}


    /**
     * @param array $rules
     * @return array|null
     * @throws Exception
     */
    public function toJSON($rules = []){
		if(!isset($rules['type'])){
			throw new Exception('Type not given');
		}
		if(!isset($rules['delete'])){
			throw new Exception('Delete not specified');
		}
		$del = !!$rules['delete'];
		switch($rules['type']){
			case 'product':
				if($del){
					return $this->pack($this->oldProductAmounts, DeleteProductAmount::class);
				}else{
					return $this->pack($this->newProductAmounts, ProductAmount::class);
				}
				break;
			case 'offer':
				if($del){
					return $this->pack($this->oldOfferAmounts, DeleteOfferAmount::class);
				}else{
					return $this->pack($this->newOfferAmounts, OfferAmount::class);
				}
				break;
			default: throw new Exception("Wrong type: {$rules['type']}");
		}
	}
	
	#gen - begin
	public function getNewProductAmounts(){ return $this->newProductAmounts; }
	public function getOldProductAmounts(){ return $this->oldProductAmounts; }
	public function getNewOfferAmounts(){ return $this->newOfferAmounts; }
	public function getOldOfferAmounts(){ return $this->oldOfferAmounts; }

	#gen - end
}