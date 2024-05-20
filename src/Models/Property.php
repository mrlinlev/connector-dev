<?php

namespace Leveon\Connector\Models;

class Property extends AModel{
	
	#prop title vgs aprot
	protected $title;
	#prop options vgs aprot
	protected $options;
	#prop multiple vgs aprot
	protected $multiple;
	#prop type vgs aprot
	protected $type;
	#prop scheme vgs aprot
	protected $scheme;
	
	protected static $valueableList = [
		'title',
		'options',
		'multiple',
		'type',
	];
	
	protected static $lists = [
		'scheme' => PropertyTuning::class
	];
	
	#gen - begin
	public function getTitle(){ return $this->title; }
	public function setTitle($title){ $this->title = $title; return $this; }
	public function getOptions(){ return $this->options; }
	public function setOptions($options){ $this->options = $options; return $this; }
	public function getMultiple(){ return $this->multiple; }
	public function setMultiple($multiple){ $this->multiple = $multiple; return $this; }
	public function getType(){ return $this->type; }
	public function setType($type){ $this->type = $type; return $this; }
	public function getScheme(){ return $this->scheme; }
	public function setScheme($scheme){ $this->scheme = $scheme; return $this; }
	#gen - end
}