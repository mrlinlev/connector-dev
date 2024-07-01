<?php

namespace Leveon\Connector\Traits\DB;

use Leveon\Connector\Exceptions\CodeException;
use Leveon\Connector\Exceptions\DBException;

trait Types
{
    /**
     * @param $localId
     * @return int | null
     * @throws CodeException
     * @throws DBException
     */
    public function outerType($localId): ?int
    {
        return $this->val('SELECT "outer" FROM types WHERE local=?', $localId);
    }

    /**
     * @param int $outerId
     * @return mixed
     * @throws CodeException
     * @throws DBException
     */
    public function localType(int $outerId): mixed
    {
        return $this->val('SELECT local FROM types WHERE "outer"=?', ["i" => $outerId]);
    }

    /**
     * @param $localId
     * @param int $outerId
     * @return void
     * @throws CodeException
     * @throws DBException
     */
    public function bindType($localId, int $outerId): void
    {
        $this->exec('INSERT INTO types ("outer", "local") VALUES (?, ?)', ['i' => $outerId], $localId);
    }

    /**
     * @param int $outerId
     * @return void
     * @throws CodeException
     * @throws DBException
     */
    public function unbindType(int $outerId): void
    {
        $this->exec('DELETE FROM types WHERE "outer" = ?', ['i' => $outerId]);
    }

    /**
     * @return void
     * @throws CodeException
     * @throws DBException
     */
    public function unbindAllTypes(): void
    {
        $this->exec('DELETE FROM types WHERE TRUE');
    }

    abstract public function val($sql, ...$params): mixed;
    abstract public function exec($sql, ...$params): void;
}