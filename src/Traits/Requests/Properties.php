<?php

namespace Leveon\Connector\Traits\Requests;

use Leveon\Connector\Exceptions\CodeException;
use Leveon\Connector\Exceptions\ConfigurationException;
use Leveon\Connector\Exceptions\DBException;
use Leveon\Connector\Models\Property;

trait Properties
{
    /**
     * @param $localId
     * @param Property $property
     * @return bool
     * @throws CodeException
     * @throws ConfigurationException
     * @throws DBException
     */
    public function upProperty($localId, Property $property): bool
    {
        $c = $this->conn;
        $outer = $this->db->outerProperty($localId);
        if ($outer === null) {
            $response = $c->process($c->post("$this->catalogPath/properties", $property->toJSON()));
            if ($response->isSuccessful()) {
                $this->db->bindProperty($localId, $response->json()->id);
            }
        } else {
            $response = $c->process($c->patch("$this->catalogPath/properties/$outer", $property->toJSON()));
        }
        return $response->isSuccessful();
    }

    /**
     * @param $localId
     * @param Property $property
     * @return bool
     * @throws CodeException
     * @throws ConfigurationException
     * @throws DBException
     */
    public function stateProperty($localId, Property $property): bool
    {
        $c = $this->conn;
        $outer = $this->db->outerProperty($localId);
        if ($outer !== null) return true;
        $response = $c->process($c->post("$this->catalogPath/properties", $property->toJSON()));
        if ($response->isSuccessful()) {
            $this->db->bindProperty($localId, $response->json()->id);
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
    public function delProperty($localId): bool
    {
        $outer = $this->db->outerProperty($localId);
        $c = $this->conn;
        $resp = $c->process($c->delete("$this->catalogPath/properties/$outer"));
        if ($resp->isSuccessful() || $resp->getCode() === 404) {
            $this->db->unbindProperty($outer);
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
    public function delAllProperties(): bool
    {
        $c = $this->conn;
        $resp = $c->process($c->delete("$this->catalogPath/properties/all"));
        if ($resp->isSuccessful()) {
            $this->db->unbindAllProperties();
        }
        return $resp->isSuccessful();
    }

    #<editor-fold defaultstate="collapsed" desc="deprecated">
    /**
     * @param $localId
     * @param Property $model
     * @return bool
     * @throws CodeException
     * @throws ConfigurationException
     * @throws DBException
     * @deprecated Use instead stateProperty() method with the same signature
     */
    public function createPropertyIfNotExists($localId, Property $model): bool
    {
        return $this->stateProperty($localId, $model);
    }
    #</editor-fold>

}