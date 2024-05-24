<?php

namespace Leveon\Connector\Models;

class ProductPrice extends APrice{
	
	#prop product vgs aprot
	protected $product;
	
	public static $compressable = [
		'currency',
		'product',
		'priceType',
	];
	
	public static $final = 'value';

    protected static array $valueableList = [
        'value',
        'currency',
        'product',
        'priceType',
    ];
	
	public function toJSON($rules = []){
		$result = [];
		if(in_array('currency', $rules) && $this->currency!==null) $result['currency'] = $this->currency;
		if(in_array('product', $rules) && $this->product!==null) $result['product'] = $this->product;
		if(in_array('priceType', $rules) && $this->priceType!==null) $result['priceType'] = $this->priceType;
		if(in_array('value', $rules) && $this->value!==null) $result['value'] = $this->value;
		return $result;
	}
	
	#gen - begin
	public function getProduct(){ return $this->product; }
	public function setProduct($product){ $this->product = $product; return $this; }
	#gen - end
}