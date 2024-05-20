<?php

namespace Leveon\Connector\Models;

class FileValue extends AModel{
	
	#prop file vgs aprot
	protected $file;
	#prop title vgs aprot
	protected $title;
	
	protected static $valueableList = ['file', 'title'];
	
	public static function Url($file, $ext = null, $title = null){
		$ext = $ext===null? '': $ext;
		return (new static())
			->setFile("url:{$ext}:{$file}")
			->setTitle($title);
	}
	
	public static function B64($file, $ext = null, $title = null){
		$file = base64_encode($file);
		return (new static())
			->setFile("url:{$ext}:{$file}")
			->setTitle($title);
	}
	
	#gen - begin
	public function getFile(){ return $this->file; }
	public function setFile($file){ $this->file = $file; return $this; }
	public function getTitle(){ return $this->title; }
	public function setTitle($title){ $this->title = $title; return $this; }
	#gen - end
	
}