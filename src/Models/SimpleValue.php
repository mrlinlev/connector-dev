<?php

namespace Leveon\Connector\Models;

class SimpleValue extends AModel{
	
	#prop value vgs aprot
	protected $value;
	
	protected static $valueableList = ['value'];
	
	public static function V($value){
		return (new static())->setValue($value);
	}
	
	#gen - begin
	public function getValue(){ return $this->value; }
	public function setValue($value){ $this->value = $value; return $this; }
	#gen - end
	
}