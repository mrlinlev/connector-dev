<?php

namespace Leveon\Connector\Traits\DB;

use Leveon\Connector\Exceptions\CodeException;
use Leveon\Connector\Exceptions\DBException;

trait Collections
{
    /**
     * @param $localId
     * @return int | null
     * @throws CodeException
     * @throws DBException
     */
    public function outerCollection($localId): ?int
    {
        return $this->val('SELECT "outer" FROM collections WHERE local=?', $localId);
    }

    /**
     * @param int $outerId
     * @return mixed
     * @throws CodeException
     * @throws DBException
     */
    public function localCollection(int $outerId): mixed
    {
        return $this->val('SELECT local FROM collections WHERE "outer"=?', ['i' => $outerId]);
    }

    /**
     * @param $localId
     * @param int $outerId
     * @param $localProductId
     * @param int $outerBrandId
     * @return void
     * @throws CodeException
     * @throws DBException
     */
    public function bindCollection($localId, int $outerId, $localProductId, int $outerBrandId): void
    {
        $this->exec(
            'INSERT INTO collections ("outer", "local", brandOuter, brandLocal) VALUES (?, ?, ?, ?)',
            ['i' => $outerId], $localId, ['i' => $outerBrandId], $localProductId
        );
    }

    /**
     * @param int $outerId
     * @return void
     * @throws CodeException
     * @throws DBException
     */
    public function unbindCollection(int $outerId): void
    {
        $this->exec('DELETE FROM collections WHERE "outer" = ?', ['i' => $outerId]);
    }

    /**
     * @return void
     * @throws CodeException
     * @throws DBException
     */
    public function unbindAllCollections(): void
    {
        $this->exec('DELETE FROM collections WHERE TRUE');
    }

    abstract public function val($sql, ...$params): mixed;
    abstract public function exec($sql, ...$params): void;
}