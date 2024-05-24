<?php

namespace Leveon\Connector\Models;

class Collection extends APropertiableModel{
	
	#prop title vgs aprot
	protected $title;
	#prop image vgs aprot
	protected $image;

    protected static array $valueableList = ['title', 'image'];
	
	#gen - begin
	public function getTitle(){ return $this->title; }
	public function setTitle($title){ $this->title = $title; return $this; }
	public function getImage(){ return $this->image; }
	public function setImage($image){ $this->image = $image; return $this; }
	#gen - end
}