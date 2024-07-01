<?php

namespace Leveon\Connector\Traits\DB;

use Leveon\Connector\Exceptions\CodeException;
use Leveon\Connector\Exceptions\DBException;

trait Brands
{

    /**
     * @param $localId
     * @return int | null
     * @throws CodeException
     * @throws DBException
     */
    public function outerBrand($localId): ?int
    {
        return $this->val('SELECT "outer" FROM brands WHERE local=?', $localId);
    }

    /**
     * @param int $outerId
     * @return mixed
     * @throws CodeException
     * @throws DBException
     */
    public function localBrand(int $outerId): mixed
    {
        return $this->val('SELECT local FROM brands WHERE "outer"=?', ["i" => $outerId]);
    }

    /**
     * @param $localId
     * @param int $outerId
     * @return void
     * @throws CodeException
     * @throws DBException
     */
    public function bindBrand($localId, int $outerId): void
    {
        $this->exec('INSERT INTO brands ("outer", "local") VALUES (?, ?)', ['i' => $outerId], $localId);
    }

    /**
     * @param int $outerId
     * @return void
     * @throws CodeException
     * @throws DBException
     */
    public function unbindBrand(int $outerId): void
    {
        $this->exec('DELETE FROM collections WHERE "brandOuter" = ?', ['i' => $outerId]);
        $this->exec('DELETE FROM brands WHERE "outer" = ?', ['i' => $outerId]);
    }

    /**
     * @return void
     * @throws CodeException
     * @throws DBException
     */
    public function unbindAllBrands(): void
    {
        $this->exec('DELETE FROM collections WHERE TRUE');
        $this->exec('DELETE FROM brands WHERE TRUE');
    }

    abstract public function val($sql, ...$params): mixed;
    abstract public function exec($sql, ...$params): void;
}