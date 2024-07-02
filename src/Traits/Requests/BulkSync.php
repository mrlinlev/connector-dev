<?php

namespace Leveon\Connector\Traits\Requests;

use Leveon\Connector\Exceptions\CodeException;
use Leveon\Connector\Exceptions\ConfigurationException;
use Leveon\Connector\Models\AmountsPacker;
use Leveon\Connector\Models\PricesPacker;
use Leveon\Connector\Models\RelationsPacker;

trait BulkSync
{
    /**
     * @param PricesPacker $packer
     * @return bool
     * @throws ConfigurationException
     * @throws CodeException
     */
    public function syncPrices(PricesPacker $packer): bool
    {
        $c = $this->conn;
        $send = $packer->toJSON(['delete' => true]);
        if ($send !== null) {
            if ($c->process($c->delete("$this->catalogPath/prices", $send))->isFailed()) return false;
        }
        $send = $packer->toJSON(['delete' => false]);
        if ($send !== null) {
            if ($c->process($c->patch("$this->catalogPath/prices", $send))->isFailed()) return false;
        }
        return true;
    }

    /**
     * @param AmountsPacker $packer
     * @return bool
     * @throws ConfigurationException
     * @throws CodeException
     */
    public function syncAmounts(AmountsPacker $packer): bool
    {
        $c = $this->conn;
        $send = $packer->toJSON(['delete' => true]);
        if ($send !== null) {
            if ($c->process($c->delete("$this->catalogPath/offer/amounts", $send))->isFailed()) return false;
        }
        $send = $packer->toJSON(['delete' => false]);
        if ($send !== null) {
            if ($c->process($c->patch("$this->catalogPath/offer/amounts", $send))->isFailed()) return false;
        }
        return true;
    }

    /**
     * @param RelationsPacker $packer
     * @return bool
     * @throws CodeException
     * @throws ConfigurationException
     */
    public function syncRelations(RelationsPacker $packer): bool
    {
        $c = $this->conn;
        $send = $packer->toJSON(['old']);
        if ($send !== null) {
            if ($c->process($c->delete("$this->catalogPath/product-relations", $send))->isFailed()) return false;
        }
        $send = $packer->toJSON(['new']);
        if ($send !== null) {
            if ($c->process($c->post("$this->catalogPath/product-relations", $send))->isFailed()) return false;
        }
        return true;
    }

}