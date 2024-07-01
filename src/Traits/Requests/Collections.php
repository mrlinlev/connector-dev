<?php

namespace Leveon\Connector\Traits\Requests;

use Leveon\Connector\Exceptions\CodeException;
use Leveon\Connector\Exceptions\ConfigurationException;
use Leveon\Connector\Exceptions\DBException;
use Leveon\Connector\Models\Collection;

trait Collections
{
    /**
     * Создать товарное предложение, если оно не создано
     * @param $brandLocalId
     * @param $localId
     * @param Collection $collection
     * @return bool
     * @throws CodeException
     * @throws ConfigurationException
     * @throws DBException
     */
    public function stateCollection($brandLocalId, $localId, Collection $collection): bool
    {
        $outer = $this->db->outerCollection($localId);
        if ($outer !== null) return true;
        $outerBrand = $this->db->outerBrand($brandLocalId);
        $c = $this->conn;
        $resp = $c->process($c->post("$this->catalogPath/brand/$outerBrand/collection", $collection->toJSON()));
        if ($resp->isSuccessful()) {
            $this->db->bindCollection($localId, $resp->json()->id, $brandLocalId, $resp->json()->brand);
        }
        return $resp->isSuccessful();
    }

    /**
     * @param $brandLocalId
     * @param $localId
     * @param Collection $model
     * @return bool
     * @throws CodeException
     * @throws ConfigurationException
     * @throws DBException
     */
    public function upCollection($brandLocalId, $localId, Collection $model): bool
    {
        $outer = $this->db->outerCollection($localId);
        $outerBrand = $this->db->outerBrand($brandLocalId);
        $c = $this->conn;
        if ($outer === null) {
            $resp = $c->process($c->post("$this->catalogPath/brand/$outerBrand/collection", $model->toJSON()));
        } else {
            $resp = $c->process($c->patch("$this->catalogPath/brand/$outerBrand/collection/$outer", $model->toJSON()));
        }
        if ($resp->isSuccessful() && $outer===null) {
            $this->db->bindCollection($localId, $resp->json()->id, $brandLocalId, $resp->json()->brand);
        }
        return $resp->isSuccessful();
    }

    /**
     * @param $localId
     * @return bool
     * @throws CodeException
     * @throws ConfigurationException
     * @throws DBException
     */
    public function delCollection($localId): bool
    {
        $outer = $this->db->outerCollection($localId);
        $c = $this->conn;
        $resp = $c->process($c->delete("$this->catalogPath/collection/$outer"));
        if ($resp->isSuccessful() || $resp->getCode() === 404) {
            $this->db->unbindCollection($outer);
            return true;
        }
        return false;
    }

    /**
     * @return bool
     * @throws CodeException
     * @throws ConfigurationException
     * @throws DBException
     */
    public function delAllCollections(): bool
    {
        $c = $this->conn;
        $resp = $c->process($c->delete("$this->catalogPath/collection/all"));
        if ($resp->isSuccessful()) {
            $this->db->unbindAllCollections();
        }
        return $resp->isSuccessful();
    }
}