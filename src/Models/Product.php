<?php

namespace Leveon\Connector\Models;

class Product extends APropertiableModel{
	
	#prop title vgs aprot
	protected $title;
	#prop image vgs aprot
	protected $image;
	#prop brand vgs aprot
	protected $brand;
	#prop collection vgs aprot
	protected $collection;
	#prop type vgs aprot
	protected $type;
	#prop article vgs aprot
	protected $article;
	#prop accountingUnit vgs aprot
	protected $accountingUnit;

    protected static array $valueableList = [
        'title',
        'image',
        'brand',
        'collection',
        'type',
        'article',
        'accountingUnit'
    ];
	
	#gen - begin
	public function getTitle(){ return $this->title; }
	public function setTitle($title){ $this->title = $title; return $this; }
	public function getImage(){ return $this->image; }
	public function setImage($image){ $this->image = $image; return $this; }
	public function getBrand(){ return $this->brand; }
	public function setBrand($brand){ $this->brand = $brand; return $this; }
	public function getCollection(){ return $this->collection; }
	public function setCollection($collection){ $this->collection = $collection; return $this; }
	public function getType(){ return $this->type; }
	public function setType($type){ $this->type = $type; return $this; }
	public function getArticle(){ return $this->article; }
	public function setArticle($article){ $this->article = $article; return $this; }
	public function getAccountingUnit(){ return $this->accountingUnit; }
	public function setAccountingUnit($accountingUnit){ $this->accountingUnit = $accountingUnit; return $this; }
	#gen - end
}