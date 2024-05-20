<?php

namespace Leveon\Connector\Models;

abstract class APrice extends AModel{
		
	#prop value vgs aprot
	protected $value;
	#prop currency vgs aprot
	protected $currency;
	#prop priceType vgs aprot
	protected $priceType;

	#gen - begin
	public function getValue(){ return $this->value; }
	public function setValue($value){ $this->value = $value; return $this; }
	public function getCurrency(){ return $this->currency; }
	public function setCurrency($currency){ $this->currency = $currency; return $this; }
	public function getPriceType(){ return $this->priceType; }
	public function setPriceType($priceType){ $this->priceType = $priceType; return $this; }
	#gen - end
}