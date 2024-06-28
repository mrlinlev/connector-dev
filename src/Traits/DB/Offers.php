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
        return $this->outerByLocal('offer', $localId);
    }

    /**
     * @param $outerId
     * @return mixed
     * @throws CodeException
     * @throws DBException
     */
    public function localOffer($outerId): mixed
    {
        return $this->localByOuter('offer', $outerId);
    }

    public function bind($localId, string $outerId, $localProductId, string $outerProductId): void
    {
        #$this->exec("INSERT INTO offers ("outer", local)");
    }

    abstract public function localByOuter($type, $outerId);
    abstract public function outerByLocal($type, $localId);
    abstract public function val($sql, ...$params): mixed;
    abstract public function exec($sql, ...$params): void;
}