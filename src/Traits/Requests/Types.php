<?php

namespace Leveon\Connector\Traits\Requests;

use Leveon\Connector\Exceptions\CodeException;
use Leveon\Connector\Exceptions\ConfigurationException;
use Leveon\Connector\Exceptions\DBException;
use Leveon\Connector\Models\ProductType;

trait Types
{
    /**
     * @param $localId
     * @param ProductType $type
     * @return bool
     * @throws CodeException
     * @throws ConfigurationException
     * @throws DBException
     */
    public function upProductType($localId, ProductType $type): bool
    {
        $c = $this->conn;
        $outer = $this->db->outerType($localId);
        if ($outer === null) {
            $response = $c->process($c->post("$this->catalogPath/type", $type->toJSON()));
            if ($response->isSuccessful()) {
                $this->db->bindType($localId, $response->json()->id);
            }
        } else {
            $response = $c->process($c->patch("$this->catalogPath/type/$outer", $type->toJSON()));
        }
        return $response->isSuccessful();
    }

    /**
     * @param $localId
     * @param ProductType $type
     * @return bool
     * @throws CodeException
     * @throws ConfigurationException
     * @throws DBException
     */
    public function stateProductType($localId, ProductType $type): bool
    {
        $c = $this->conn;
        $outer = $this->db->outerType($localId);
        if ($outer !== null) return true;
        $response = $c->process($c->post("$this->catalogPath/type", $type->toJSON()));
        if ($response->isSuccessful()) {
            $this->db->bindType($localId, $response->json()->id);
        }
        return $response->isSuccessful();
    }

    /**
     * @param $localId
     * @return bool
     * @throws CodeException
     * @throws ConfigurationException
     * @throws DBException
     */
    public function delProductType($localId): bool
    {
        $outer = $this->db->outerType($localId);
        $c = $this->conn;
        $resp = $c->process($c->delete("$this->catalogPath/type/$outer"));
        if ($resp->isSuccessful() || $resp->getCode() === 404) {
            $this->db->unbindType($outer);
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
    public function delAllProductTypes(): bool
    {
        $c = $this->conn;
        $resp = $c->process($c->delete("$this->catalogPath/type/all"));
        if ($resp->isSuccessful()) {
            $this->db->unbindAllTypes();
        }
        return $resp->isSuccessful();
    }

    #<editor-fold defaultstate="collapsed" desc="deprecated">
    /**
     * @param $localId
     * @param ProductType $model
     * @return bool
     * @throws CodeException
     * @throws ConfigurationException
     * @throws DBException
     * @deprecated Use instead stateProductType() method with the same signature
     */
    public function createProductTypeIfNotExists($localId, ProductType $model): bool
    {
        return $this->stateProductType($localId, $model);
    }
    #</editor-fold>

}