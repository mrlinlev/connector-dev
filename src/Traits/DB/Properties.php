<?php

namespace Leveon\Connector\Traits\DB;

use Leveon\Connector\Exceptions\CodeException;
use Leveon\Connector\Exceptions\DBException;

trait Properties
{
    /**
     * @param $localId
     * @return int | null
     * @throws CodeException
     * @throws DBException
     */
    public function outerProperty($localId): ?int
    {
        return $this->val('SELECT "outer" FROM properties WHERE local=?', $localId);
    }

    /**
     * @param int $outerId
     * @return mixed
     * @throws CodeException
     * @throws DBException
     */
    public function localProperties(int $outerId): mixed
    {
        return $this->val('SELECT local FROM properties WHERE "outer"=?', ["i" => $outerId]);
    }

    /**
     * @param $localId
     * @param int $outerId
     * @return void
     * @throws CodeException
     * @throws DBException
     */
    public function bindProperty($localId, int $outerId): void
    {
        $this->exec('INSERT INTO properties ("outer", "local") VALUES (?, ?)', ['i' => $outerId], $localId);
    }

    /**
     * @param int $outerId
     * @return void
     * @throws CodeException
     * @throws DBException
     */
    public function unbindProperty(int $outerId): void
    {
        $this->exec('DELETE FROM properties WHERE "outer" = ?', ['i' => $outerId]);
    }

    /**
     * @return void
     * @throws CodeException
     * @throws DBException
     */
    public function unbindAllProperties(): void
    {
        $this->exec('DELETE FROM properties WHERE TRUE');
    }

    abstract public function val($sql, ...$params): mixed;
    abstract public function exec($sql, ...$params): void;
}