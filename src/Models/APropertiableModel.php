<?php

namespace Leveon\Connector\Models;

abstract class APropertiableModel extends AModel{
	
	#prop properties vgs aprot
	protected $properties = [];

	protected static array $lists = [
		'properties' => PropertyValue::class
	];	
	
	public function setProperty($property, $value){
		$this->properties[] = (is_array($value)? MultiPropertyValue::New(): SinglePropertyValue::New())
			->setProperty($property)
			->setValue($value);
		return $this;
	}
	
	#gen - begin
	public function getProperties(){ return $this->properties; }
	public function setProperties($properties){ $this->properties = $properties; return $this; }
	#gen - end
}