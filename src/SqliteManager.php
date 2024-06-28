<?php

namespace Leveon\Connector;

use Exception;
use SQLite3;

class SqliteManager{
	
	private string $path;
	private SQLite3 $sqlite;

    /**
     * @throws Exception
     */
    public function __construct(){
		$this->path = Leveon::getDbPath();
		if(!file_exists($this->path)){
			$this->init();
		}
		$this->sqlite = new SQLite3($this->path);
		$this->sqlite->busyTimeout(250);
		$this->sqlite->exec('PRAGMA journal_mode = wal;');
	}
	
	private function init(): void
    {
		$this->sqlite = new SQLite3($this->path);
		$this->exec('CREATE TABLE migrations (name string PRIMARY KEY, applied DATE)');
		$this->exec('CREATE TABLE brands (outer INT PRIMARY KEY, local TEXT UNIQUE)');
		$this->exec('CREATE TABLE collections (outer INT PRIMARY KEY, local TEXT UNIQUE)');
		$this->exec('CREATE TABLE types (outer INT PRIMARY KEY, local TEXT UNIQUE)');
		$this->exec('CREATE TABLE properties (outer INT PRIMARY KEY, local TEXT UNIQUE)');
		$this->exec('CREATE TABLE products (outer TEXT PRIMARY KEY, local TEXT UNIQUE)');
		$this->exec('CREATE TABLE offers (outer TEXT PRIMARY KEY, local TEXT UNIQUE)');
		$this->close();
	}

    /**
     * @throws Exception
     */
    public function outerByLocal($type, $localId){
		return $this->val("SELECT outer FROM {$this->tableByType($type)} WHERE local = ?", $localId);
	}

    /**
     * @throws Exception
     */
    public function localByOuter($type, $outerId){
		return $this->val("SELECT local FROM {$this->tableByType($type)} WHERE outer = ?", $this->typeOuterId($type, $outerId));
	}

    /**
     * @throws Exception
     */
    public function all($type): array
    {
		return $this->getAll("SELECT * FROM {$this->tableByType($type)}");
	}

    /**
     * @throws Exception
     */
    public function bind($type, $localId, $outerId): void
    {
		$this->exec("INSERT INTO {$this->tableByType($type)} (outer, local) VALUES (?, ?)", $this->typeOuterId($type, $outerId), $localId);
	}

    /**
     * @throws Exception
     */
    public function unbind($type, $localId): void
    {
		$this->exec("DELETE FROM {$this->tableByType($type)} WHERE local = ?", $localId);
	}

    /**
     * @throws Exception
     */
    public function unbindOuter($type, $outerId): void
    {
		$this->exec("DELETE FROM {$this->tableByType($type)} WHERE outer = ?", $this->typeOuterId($type, $outerId));
	}

    /**
     * Получение фильтра сущности по типу сущности и значению
     * @param $type - тип сущности
     * @param $outerId - id в каталоге Leveon
     * @return array | string
     * @throws Exception
     */
    private function typeOuterId($type, $outerId): array|string
    {
        return match ($type) {
            'brand', 'collection', 'type', 'property' => ['i' => $outerId],
            'product', 'offer' => $outerId,
            default => throw new Exception("Unknown type `$type`"),
        };
	}

    /**
     * Получение таблицы сущности по типу сущности
     * @param $type - тип сущности
     * @return string
     * @throws Exception
     */
    private function tableByType($type): string
    {
        return match ($type) {
            'brand' => 'brands',
            'collection' => 'collections',
            'type' => 'types',
            'property' => 'properties',
            'product' => 'products',
            'offer' => 'offers',
            default => throw new Exception("Unknown type `$type`"),
        };
	}
	
	public function val($sql, ...$params): mixed
    {
		return $this->sqlite->querySingle($this->prepare($sql, $params));
	}
	
	public function getAll($sql, ...$params): array
    {
		$qr = $this->sqlite->query($this->prepare($sql, $params));
		$result = [];
		while ($row = $qr->fetchArray()) {
		  $result[] = $row;
		}
		return $result;
	}
	
	public function exec($sql, ...$params): void
    {
		$this->sqlite->exec($this->prepare($sql, $params));
	}
	
	public function esc($str): string
    {
		return $this->sqlite->escapeString($str);
	}
	
	public function close(): void
    {
		$this->sqlite->close();
	}
	
	public function prepare($sql, $params = []): bool|string
    {
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
							$stmt->bindValue($i+1, $value);
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
        return $stmt->getSql(true);
	}

	public function getPath(): string
    {
        return $this->path;
    }
}