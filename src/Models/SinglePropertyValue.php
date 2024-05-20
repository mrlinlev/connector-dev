<?php

namespace Leveon\Connector\Models;

class SinglePropertyValue extends AModel{
	
	#prop property vgs aprot
	protected $property;
	#prop value vgs aprot
	protected $value;
	
	protected static $valueableList = ['property'];
	protected static $entitiesList = [
		'value' => AModel::class
	];	
	
	#gen - begin
	public function getProperty(){ return $this->property; }
	public function setProperty($property){ $this->property = $property; return $this; }
	public function getValue(){ return $this->value; }
	public function setValue($value){ $this->value = $value; return $this; }
	#gen - end
}