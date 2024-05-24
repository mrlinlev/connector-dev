<?php

namespace Leveon\Connector\Models;

class PropertyTuning extends AModel{
	
	#prop defaultValue vgs aprot
	protected $defaultValue;
	#prop defaultUnit vgs aprot
	protected $defaultUnit;
	#prop unitsGroup vgs aprot
	protected $unitsGroup;
	#prop storageUnit vgs aprot
	protected $storageUnit;
	#prop displayUnit vgs aprot
	protected $displayUnit;

    protected static array $valueableList = [
        'defaultValue',
        'defaultUnit',
        'unitsGroup',
        'storageUnit',
        'displayUnit',
    ];
			
	#gen - begin
	public function getDefaultValue(){ return $this->defaultValue; }
	public function setDefaultValue($defaultValue){ $this->defaultValue = $defaultValue; return $this; }
	public function getDefaultUnit(){ return $this->defaultUnit; }
	public function setDefaultUnit($defaultUnit){ $this->defaultUnit = $defaultUnit; return $this; }
	public function getUnitsGroup(){ return $this->unitsGroup; }
	public function setUnitsGroup($unitsGroup){ $this->unitsGroup = $unitsGroup; return $this; }
	public function getStorageUnit(){ return $this->storageUnit; }
	public function setStorageUnit($storageUnit){ $this->storageUnit = $storageUnit; return $this; }
	public function getDisplayUnit(){ return $this->displayUnit; }
	public function setDisplayUnit($displayUnit){ $this->displayUnit = $displayUnit; return $this; }
	#gen - end
}