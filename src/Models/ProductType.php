<?php

namespace Leveon\Connector\Models;

class ProductType extends APropertiableModel{
	
	#prop title vgs aprot
	protected $title;
	#prop parent vgs aprot
	protected $parent = 0;

    protected static array $valueableList = ['title', 'parent'];

	#gen - begin
	public function getTitle(){ return $this->title; }
	public function setTitle($title){ $this->title = $title; return $this; }
	public function getParent(){ return $this->parent; }
	public function setParent($parent){ $this->parent = $parent; return $this; }
	#gen - end
	
}