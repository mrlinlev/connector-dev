<?php

namespace Leveon\Connector\Models;

abstract class ADeletePrice extends AModel{
	
	#prop priceType vgs aprot
	protected $priceType;
	
	#gen - begin
	public function getPriceType(){ return $this->priceType; }
	public function setPriceType($priceType){ $this->priceType = $priceType; return $this; }
	#gen - end
}