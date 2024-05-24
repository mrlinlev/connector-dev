<?php

namespace Leveon\Connector\Models;

class DeleteOfferPrice extends ADeletePrice{
	
	#prop offer vgs aprot
	protected $offer;
	
	public static $compressable = [
		'offer',
		'priceType',
	];
	
	public static $final = null;

    protected static array $valueableList = [
        'product',
        'store',
    ];


    #gen - begin
	public function getOffer(){ return $this->offer; }
	public function setOffer($offer){ $this->offer = $offer; return $this; }
	#gen - end
}