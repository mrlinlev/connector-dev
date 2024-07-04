<?php

namespace Leveon\Connector\Traits\DB;

use Leveon\Connector\Exceptions\CodeException;
use Leveon\Connector\Exceptions\DBException;

trait Products
{

    /**
     * @param $localId
     * @return string | null
     * @throws CodeException
     * @throws DBException
     */
    public function outerProduct($localId): ?string
    {
        return $this->val('SELECT "outer" FROM products WHERE local=?', $localId);
    }

    /**
     * @param string $outerId
     * @return string
     * @throws CodeException
     * @throws DBException
     */
    public function localProduct(string $outerId): mixed
    {
        return $this->val('SELECT local FROM products WHERE "outer"=?', $outerId);
    }

    /**
     * @param $localId
     * @param string $outerId
     * @return void
     * @throws CodeException
     * @throws DBException
     */
    public function bindProduct($localId, string $outerId): void
    {
        $this->exec('INSERT INTO products ("outer", "local") VALUES (?, ?)', $outerId, $localId);
    }

    /**
     * @param string $outerId
     * @return void
     * @throws CodeException
     * @throws DBException
     */
    public function unbindProduct(string $outerId): void
    {
        $this->exec('DELETE FROM offers WHERE "productOuter" = ?', $outerId);
        $this->exec('DELETE FROM products WHERE "outer" = ?', $outerId);
    }


    /**
     * @return void
     * @throws CodeException
     * @throws DBException
     */
    public function unbindAllProducts(): void
    {
        $this->exec('DELETE FROM offers WHERE TRUE');
        $this->exec('DELETE FROM products WHERE TRUE');
    }

    abstract public function localByOuter($type, $outerId);
    abstract public function outerByLocal($type, $localId);
    abstract public function val($sql, ...$params): mixed;
    abstract public function exec($sql, ...$params): void;
}