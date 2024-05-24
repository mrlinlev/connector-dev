<?php

namespace Leveon\Connector\Models;

class DeleteProductAmount extends AModel{
	
	#prop product vgs aprot
	protected $product;
	#prop store vgs aprot
	protected $store;

    public static array $compressable = [
        'product',
        'store',
    ];
	
	public static $final = null;

    protected static array $valueableList = [
        'product',
        'store',
    ];
	
	public function toJSON($rules = []){
		$result = [];
		if(in_array('product', $rules) && $this->product!==null) $result['product'] = $this->product;
		if(in_array('store', $rules) && $this->store!==null) $result['store'] = $this->store;
		return $result;
	}
	
	#gen - begin
	public function getProduct(){ return $this->product; }
	public function setProduct($product){ $this->product = $product; return $this; }
	public function getStore(){ return $this->store; }
	public function setStore($store){ $this->store = $store; return $this; }
	#gen - end
}