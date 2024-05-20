<?php

namespace Leveon\Connector\Models;

class PricesPacker extends APacker{
	
	#prop newProductPrices vgS aprot
	protected $newProductPrices = [];
	#prop oldProductPrices vgS aprot
	protected $oldProductPrices = [];
	#prop newOfferPrices vgS aprot
	protected $newOfferPrices = [];
	#prop oldOfferPrices vgS aprot
	protected $oldOfferPrices = [];
		
	public function add($value){
		if($value instanceof ProductPrice){
			$this->newProductPrices[] = $value;
			return $this;
		}
		if($value instanceof OfferPrice){
			$this->newOfferPrices[] = $value;
			return $this;
		}
		if($value instanceof DeleteProductPrice){
			$this->oldProductPrices[] = $value;
			return $this;
		}
		if($value instanceof DeleteOfferPrice){
			$this->oldOfferPrices[] = $value;
			return $this;
		}
		throw new \Exception('Unknown value');
	}
	
		
	public function toJSON($rules = []){
		if(!isset($rules['type'])){
			throw new \Exception('Type not given');
		}
		if(!isset($rules['delete'])){
			throw new \Exception('Delete not specified');
		}
		$del = !!$rules['delete'];
		switch($rules['type']){
			case 'product':
				if($del){
					return $this->pack($this->oldProductPrices, DeleteProductPrice::class);
				}else{
					return $this->pack($this->newProductPrices, ProductPrice::class);
				}
				break;
			case 'offer':
				if($del){
					return $this->pack($this->oldOfferPrices, DeleteOfferPrice::class);
				}else{
					return $this->pack($this->newOfferPrices, OfferPrice::class);
				}
				break;
			default: throw new \Exception("Wrong type: {$rules['type']}");
		}
	}
	
	#gen - begin
	public function getNewProductPrices(){ return $this->newProductPrices; }
	public function getOldProductPrices(){ return $this->oldProductPrices; }
	public function getNewOfferPrices(){ return $this->newOfferPrices; }
	public function getOldOfferPrices(){ return $this->oldOfferPrices; }

	#gen - end
}