<?php

namespace Leveon\Connector\Models;

class OfferAmount extends AModel{
	
	#prop offer vgs aprot
	protected $offer;
	#prop store vgs aprot
	protected $store;
	#prop amount vgs aprot
	protected $amount;
	
	public static $compressable = [
		'offer',
		'store',
	];
	
	public static $final = 'amount';

    protected static array $valueableList = [
        'amount',
        'offer',
        'store',
    ];
	
	public function toJSON($rules = []){
		$result = [];
		if(in_array('offer', $rules) && $this->offer!==null) $result['offer'] = $this->offer;
		if(in_array('store', $rules) && $this->store!==null) $result['store'] = $this->store;
		if(in_array('amount', $rules) && $this->amount!==null) $result['amount'] = $this->amount;
		return $result;
	}
		
	#gen - begin
	public function getOffer(){ return $this->offer; }
	public function setOffer($offer){ $this->offer = $offer; return $this; }
	public function getStore(){ return $this->store; }
	public function setStore($store){ $this->store = $store; return $this; }
	public function getAmount(){ return $this->amount; }
	public function setAmount($amount){ $this->amount = $amount; return $this; }
	#gen - end
}