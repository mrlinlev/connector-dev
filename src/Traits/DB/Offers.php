<?php

namespace Leveon\Connector\Traits\DB;

use Leveon\Connector\Exceptions\CodeException;
use Leveon\Connector\Exceptions\DBException;

trait Offers
{
    /**
     * @param $localId
     * @return string | null
     * @throws CodeException
     * @throws DBException
     */
    public function outerOffer($localId): ?string
    {
        return $this->val('SELECT "outer" FROM brands WHERE local=?', $localId);
    }

    /**
     * @param string $outerId
     * @return mixed
     * @throws CodeException
     * @throws DBException
     */
    public function localOffer(string $outerId): mixed
    {
        return $this->val('SELECT local FROM offers WHERE "outer"=?', $outerId);
    }

    /**
     * @param $localId
     * @param string $outerId
     * @param $localProductId
     * @param string $outerProductId
     * @return void
     * @throws CodeException
     * @throws DBException
     */
    public function bindOffer($localId, string $outerId, $localProductId, string $outerProductId): void
    {
        $this->exec(
            'INSERT INTO offers ("outer", "local", productOuter, productLocal) VALUES (?, ?, ?, ?)',
            $outerId, $localId, $outerProductId, $localProductId
        );
    }

    /**
     * @param string $outerId
     * @return void
     * @throws CodeException
     * @throws DBException
     */
    public function unbindOffer(string $outerId): void
    {
        $this->exec('DELETE FROM offers WHERE "outer" = ?', $outerId);
    }

    abstract public function val($sql, ...$params): mixed;
    abstract public function exec($sql, ...$params): void;
}