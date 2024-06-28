<?php

namespace Leveon\Connector\Models;

abstract class AModelWithProperties extends AModel{
	
	protected $properties = [];

	protected static array $lists = [
		'properties' => APropertyValue::class
	];

    /**
     * @param int $property
     * @param $value
     * @return $this
     */
    public function setProperty(int $property, $value): static
    {
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