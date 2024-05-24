<?php

namespace Leveon\Connector\Models;

class MultiPropertyValue extends APropertyValue{
	
	protected $value;

    protected static array $valueableList = ['property'];
    protected static array $lists = [
        'value' => AModel::class
    ];
	
	
	public function getValue(){ return $this->value; }
	public function setValue($value){ $this->value = $value; return $this; }
}