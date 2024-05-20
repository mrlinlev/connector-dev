<?php

namespace Leveon\Connector\Models;

class DeleteProductPrice extends ADeletePrice{
	
	#prop product vgs aprot
	protected $product;
	
	public static $compressable = [
		'product',
		'priceType',
	];
	
	public static $final = null;
			
	#gen - begin
	public function getProduct(){ return $this->product; }
	public function setProduct($product){ $this->product = $product; return $this; }
	#gen - end
}