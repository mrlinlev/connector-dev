<?php

namespace Leveon\Connector\Models;

class ProductAmount extends AModel{
	
	#prop product vgs aprot
	protected $product;
	#prop store vgs aprot
	protected $store;
	#prop amount vgs aprot
	protected $amount;
	
	public static $compressable = [
		'product',
		'store',
	];
	
	public static $final = 'amount';
	
	protected static $valueableList = [
		'amount',
		'product',
		'store',
	];
	
	public function toJSON($rules = []){
		$result = [];
		if(in_array('product', $rules) && $this->product!==null) $result['product'] = $this->product;
		if(in_array('store', $rules) && $this->store!==null) $result['store'] = $this->store;
		if(in_array('amount', $rules) && $this->amount!==null) $result['amount'] = $this->amount;
		return $result;
	}
	
	#gen - begin
	public function getProduct(){ return $this->product; }
	public function setProduct($product){ $this->product = $product; return $this; }
	public function getStore(){ return $this->store; }
	public function setStore($store){ $this->store = $store; return $this; }
	public function getAmount(){ return $this->amount; }
	public function setAmount($amount){ $this->amount = $amount; return $this; }
	#gen - end
}