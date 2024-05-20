<?php

namespace Leveon\Connector;

class SqliteManager{
	
	#prop path vgS
	private $path;
	#prop sqlite vGS
	private $sqlite;
	
	public function __construct(){
		$this->path = __DIR__."/sdb.sqlite";
		if(!file_exists($this->path)){
			$this->init();
		}
		$this->sqlite = new \SQLite3($this->path);
		$this->sqlite->busyTimeout(250);
		$this->sqlite->exec('PRAGMA journal_mode = wal;');
	}
	
	private function init(){
		$this->sqlite = new \SQLite3($this->path);
		$this->exec('CREATE TABLE brands (outer INT PRIMARY KEY, local TEXT UNIQUE)');
		$this->exec('CREATE TABLE collections (outer INT PRIMARY KEY, local TEXT UNIQUE)');
		$this->exec('CREATE TABLE types (outer INT PRIMARY KEY, local TEXT UNIQUE)');
		$this->exec('CREATE TABLE properties (outer INT PRIMARY KEY, local TEXT UNIQUE)');
		$this->exec('CREATE TABLE products (outer TEXT PRIMARY KEY, local TEXT UNIQUE)');
		$this->exec('CREATE TABLE offers (outer TEXT PRIMARY KEY, local TEXT UNIQUE)');
		$this->close();
	}
	
	public function outerByLocal($type, $localId){
		return $this->val("SELECT outer FROM {$this->tableByType($type)} WHERE local = ?", $localId);
	}
	
	public function localByOuter($type, $outerId){
		return $this->val("SELECT local FROM {$this->tableByType($type)} WHERE outer = ?", $this->typeOuterId($type, $outerId));
	}
	
	public function all($type){
		return $this->getAll("SELECT * FROM {$this->tableByType($type)}");
	}
	
	public function bind($type, $localId, $outerId){
		$this->exec("INSERT INTO {$this->tableByType($type)} (outer, local) VALUES (?, ?)", $this->typeOuterId($type, $outerId), $localId);
	}
	
	public function unbind($type, $localId){
		$this->exec("DELETE FROM {$this->tableByType($type)} WHERE local = ?", $localId);
	}
	
	public function unbindOuter($type, $outerId){
		$this->exec("DELETE FROM {$this->tableByType($type)} WHERE outer = ?", $this->typeOuterId($type, $outerId));
	}
	
	private function typeOuterId($type, $outerId){
		switch($type){
			case 'brand':
			case 'collection':
			case 'type':
			case 'property': return ['i'=>$outerId];
			case 'product': 
			case 'offer': return $outerId;
			default: throw new \Excption("Unknown type `{$type}`");
		}
	}
	
	private function tableByType($type){
		switch($type){
			case 'brand': return 'brands';
			case 'collection': return 'collections';
			case 'type': return 'types';
			case 'property': return 'properties';
			case 'product': return 'products';
			case 'offer': return 'offers';
			default: throw new \Exception("Unknown type `{$type}`");
		}
	}
	
	public function val($sql, ...$params){
		return $this->sqlite->querySingle($this->prepare($sql, $params));
	}
	
	public function getAll($sql, ...$params){
		$qr = $this->sqlite->query($this->prepare($sql, $params));
		$result = [];
		while ($row = $qr->fetchArray()) {
		  $result[] = $row;
		}
		return $result;
	}
	
	public function exec($sql, ...$params){
		$this->sqlite->exec($this->prepare($sql, $params));
	}
	
	public function esc($str){
		return $this->sqlite->escapeString($str);
	}
	
	public function close(){
		$this->sqlite->close();
	}
	
	public function prepare($sql, $params = []){
		$stmt = $this->sqlite->prepare($sql);
		foreach($params as $i => $param){
			if(is_array($param)){
				foreach($param as $type => $value){
					switch($type){
						case 'b':
						case 'i':
							$stmt->bindValue($i+1, $value, SQLITE3_INTEGER);
							break;
						case 's':
							$stmt->bindValue($i+1, $value, SQLITE3_TEXT);
							break;
						case 'f':
							$stmt->bindValue($i+1, $value, SQLITE3_FLOAT);
							break;
						case 'n':
							$stmt->bindValue($i+1, $value, SQLITE3_NULL);
							break;
					}
				}
			}else{
				$stmt->bindValue($i+1, $param);
			}
		}
		$sql = $stmt->getSql(true);
		#var_dump($sql);
		return $sql;
	}
	
	
	
	#gen - begin
	public function getPath(){ return $this->path; }

	#gen - end
}