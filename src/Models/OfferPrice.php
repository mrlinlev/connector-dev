<?php

namespace Leveon\Connector\Models;

class OfferPrice extends APrice{
	
	#prop offer vgs aprot
	protected $offer;
	
	public static $compressable = [
		'currency',
		'offer',
		'priceType',
	];
	
	public static $final = 'value';
	
	protected static $valueableList = [
		'value',
		'currency',
		'offer',
		'priceType',
	];
	
	public function toJSON($rules = []){
		$result = [];
		if(in_array('currency', $rules) && $this->currency!==null) $result['currency'] = $this->currency;
		if(in_array('offer', $rules) && $this->offer!==null) $result['offer'] = $this->offer;
		if(in_array('priceType', $rules) && $this->priceType!==null) $result['priceType'] = $this->priceType;
		if(in_array('value', $rules) && $this->value!==null) $result['value'] = $this->value;
		return $result;
	}

	
	#gen - begin
	public function getOffer(){ return $this->offer; }
	public function setOffer($offer){ $this->offer = $offer; return $this; }
	#gen - end
}