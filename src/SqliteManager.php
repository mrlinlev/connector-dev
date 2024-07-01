<?php

namespace Leveon\Connector;

use Exception;
use Leveon\Connector\Exceptions\CodeException;
use Leveon\Connector\Exceptions\ConfigurationException;
use Leveon\Connector\Exceptions\DBException;
use Leveon\Connector\Traits\DB\Brands;
use Leveon\Connector\Traits\DB\Collections;
use Leveon\Connector\Traits\DB\Offers;
use Leveon\Connector\Traits\DB\Products;
use Leveon\Connector\Traits\DB\Properties;
use Leveon\Connector\Traits\DB\Types;
use SQLite3;

class SqliteManager{

    use Offers;
    use Products;
    use Brands;
    use Types;
    use Collections;
    use Properties;

    static SqliteManager $instance;
	private string $path;
	private SQLite3 $sqlite;

    /**
     * @throws ConfigurationException
     * @throws DBException
     * @throws CodeException
     */
    public function __construct(){
		$this->path = Leveon::getDbPath();
		if(!file_exists($this->path)){
			$this->init();
		}
		$this->sqlite = new SQLite3($this->path);
        $this->sqlC($this->sqlite->busyTimeout(250));
		$this->sqlC($this->sqlite->exec('PRAGMA journal_mode = wal'));
        self::$instance = $this;
    }

    /**
     * @param $any
     * @return mixed
     * @throws DBException
     */
    private function sqlC($any): mixed{
        if($any===false) throw new DBException($this->sqlite->lastErrorMsg(), $this->sqlite->lastErrorCode());
        return $any;
    }

    /**
     * @throws DBException
     * @throws CodeException
     */
    private function init(): void
    {
		$this->sqlite = new SQLite3($this->path);
		$this->exec('CREATE TABLE migrations (name string PRIMARY KEY, applied TEXT)');
		$this->close();
	}

    /**
     * @throws CodeException
     * @throws DBException
     */
    public function outerByLocal($type, $localId){
		return $this->val("SELECT outer FROM {$this->tableByType($type)} WHERE local = ?", $localId);
	}

    /**
     * @throws CodeException
     * @throws DBException
     */
    public function localByOuter($type, $outerId){
		return $this->val("SELECT local FROM {$this->tableByType($type)} WHERE outer = ?", $this->typeOuterId($type, $outerId));
	}

    /**
     * @throws CodeException
     * @throws DBException
     */
    public function all($type): array
    {
		return $this->getAll("SELECT * FROM {$this->tableByType($type)}");
	}

    /**
     * @throws CodeException
     * @throws DBException
     */
    public function bind($type, $localId, $outerId): void
    {
		$this->exec("INSERT INTO {$this->tableByType($type)} (outer, local) VALUES (?, ?)", $this->typeOuterId($type, $outerId), $localId);
	}

    /**
     * @throws CodeException
     * @throws DBException
     */
    public function unbind($type, $localId): void
    {
		$this->exec("DELETE FROM {$this->tableByType($type)} WHERE local = ?", $localId);
	}

    /**
     * @throws CodeException
     * @throws DBException
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
     * @throws CodeException
     */
    private function typeOuterId($type, $outerId): array|string
    {
        return match ($type) {
            'brand', 'collection', 'type', 'property' => ['i' => $outerId],
            'product', 'offer' => $outerId,
            default => throw new CodeException("Unknown type `$type`"),
        };
	}

    /**
     * Получение таблицы сущности по типу сущности
     * @param $type - тип сущности
     * @return string
     * @throws CodeException
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
            default => throw new CodeException("Unknown type `$type`"),
        };
	}

    /**
     * @param $sql
     * @param ...$params
     * @return mixed
     * @throws CodeException
     * @throws DBException
     */
    public function val($sql, ...$params): mixed
    {
		return $this->sqlC($this->sqlite->querySingle($this->prepare($sql, $params)));
	}

    /**
     * @param $sql
     * @param ...$params
     * @return array
     * @throws CodeException
     * @throws DBException
     */
    public function getAll($sql, ...$params): array
    {
		$qr = $this->sqlC($this->sqlite->query($this->prepare($sql, $params)));
		$result = [];
		while ($row = $qr->fetchArray()) {
		  $result[] = $row;
		}
		return $result;
	}

    /**
     * @param $sql
     * @param ...$params
     * @return void
     * @throws CodeException
     * @throws DBException
     */
    public function exec($sql, ...$params): void
    {
        $this->sqlC($this->sqlite->exec($this->prepare($sql, $params)));
	}
	
	public function esc($str): string
    {
		return $this->sqlite->escapeString($str);
	}

    /**
     * @return void
     * @throws DBException
     */
    public function close(): void
    {
        $this->sqlC($this->sqlite->close());
	}

    /**
     * @param string $sql
     * @param array $params
     * @return bool|string
     * @throws CodeException
     * @throws DBException
     */
    public function prepare(string $sql, array $params = []): bool|string
    {
		$stmt = $this->sqlC($this->sqlite->prepare($sql));
        foreach($params as $i => $param){
			if(is_array($param)){
				foreach($param as $type => $value){
                    $dbType = match ($type) {
                        'b', 'i' => SQLITE3_INTEGER,
                        'f' => SQLITE3_FLOAT,
                        'n' => SQLITE3_NULL,
                        's' => SQLITE3_TEXT,
                        default => throw new CodeException("Unknown type `$type`"),
                    };
                    $this->sqlC($stmt->bindValue($i+1, $value, $dbType));
				}
			}else{
                $this->sqlC($stmt->bindValue($i+1, $param));
			}
		}
        return $this->sqlC($stmt->getSql(true));
	}

    /**
     * @param $name
     * @return bool
     * @throws CodeException
     * @throws DBException
     */
    private function isMigrationApplied($name): bool
    {
        return $this->val('SELECT name FROM migrations  WHERE name = ?', ["s" => $name])===$name;
    }

    /**
     * @param $name
     * @return void
     * @throws CodeException
     * @throws DBException
     */
    private function markMigrationApplied($name): void
    {
        $this->exec('INSERT INTO migrations (name, applied) VALUES(?, ?)', ["s" => $name], ["s" => date('Y-m-d H:i:s')]);
    }

    /**
     * @param $name
     * @return void
     * @throws CodeException
     * @throws DBException
     */
    private function markMigrationRolledBack($name): void
    {
        $this->exec('DELETE FROM migrations WHERE name = ?', ["s" => $name]);
    }

    /**
     * @param string $migrationName
     * @return void
     * @throws CodeException
     * @throws DBException
     */
    public function upMigration(string $migrationName): void {
        if ($this->isMigrationApplied($migrationName)) return;
        echo "Applying migration $migrationName...\n";
        $migrationClass = "Leveon\\Connector\\Migrations\\$migrationName";
        $migration = new $migrationClass();
        $this->exec('BEGIN');
        try {
            $migration->up($this);
            $this->markMigrationApplied($migrationName);
            $this->exec('COMMIT');
        }catch (Exception $e){
            $this->exec('ROLLBACK');
            var_dump($e->getMessage());
            die("Fail to apply migration $migrationName");
        }
    }

    /**
     * @param string $migrationName
     * @return void
     * @throws CodeException
     * @throws DBException
     */
    public function downMigration(string $migrationName): void {
        if (!$this->isMigrationApplied($migrationName)) return;
        echo "Rolling back migration $migrationName...\n";
        $migrationClass = "Leveon\\Connector\\Migrations\\$migrationName";
        $migration = new $migrationClass();
        $this->exec('BEGIN');
        try {
            $migration->down($this);
            $this->markMigrationRolledBack($migrationName);
            $this->exec('COMMIT');
        }catch (Exception $e){
            $this->exec('ROLLBACK');
            var_dump($e->getMessage());
            die("Fail to rollback migration $migrationName");
        }
    }
}