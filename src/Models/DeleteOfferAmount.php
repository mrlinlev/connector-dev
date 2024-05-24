<?php

namespace Leveon\Connector\Models;

class DeleteOfferAmount extends AModel{
	
	#prop offer vgs aprot
	protected $offer;
	#prop store vgs aprot
	protected $store;
	
	public static $compressable = [
		'offer',
		'store',
	];
	
	public static $final = null;

    protected static array $valueableList = [
        'offer',
        'store',
    ];
	
	public function toJSON($rules = []){
		$result = [];
		if(in_array('offer', $rules) && $this->offer!==null) $result['offer'] = $this->offer;
		if(in_array('store', $rules) && $this->store!==null) $result['store'] = $this->store;
		return $result;
	}
		
	#gen - begin
	public function getOffer(){ return $this->offer; }
	public function setOffer($offer){ $this->offer = $offer; return $this; }
	public function getStore(){ return $this->store; }
	public function setStore($store){ $this->store = $store; return $this; }
	#gen - end
}