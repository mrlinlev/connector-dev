<?php

namespace Leveon\Connector\Traits\Requests;

use Leveon\Connector\Exceptions\CodeException;
use Leveon\Connector\Exceptions\ConfigurationException;
use Leveon\Connector\Exceptions\DBException;
use Leveon\Connector\Models\Brand;

trait Brands
{
    /**
     * @param $localId
     * @param Brand $brand
     * @return bool
     * @throws CodeException
     * @throws ConfigurationException
     * @throws DBException
     */
    public function upBrand($localId, Brand $brand): bool
    {
        $c = $this->conn;
        $outer = $this->db->outerBrand($localId);
        if ($outer === null) {
            $response = $c->process($c->post("$this->catalogPath/brand", $brand->toJSON()));
            if ($response->isSuccessful()) {
                $this->db->bindBrand($localId, $response->json()->id);
            }
        } else {
            $response = $c->process($c->patch("$this->catalogPath/brand/$outer", $brand->toJSON()));
        }
        return $response->isSuccessful();
    }

    /**
     * @param $localId
     * @param Brand $brand
     * @return bool
     * @throws CodeException
     * @throws ConfigurationException
     * @throws DBException
     */
    public function stateBrand($localId, Brand $brand): bool
    {
        $c = $this->conn;
        $outer = $this->db->outerBrand($localId);
        if ($outer !== null) return true;
        $response = $c->process($c->post("$this->catalogPath/brand", $brand->toJSON()));
        if ($response->isSuccessful()) {
            $this->db->bindBrand($localId, $response->json()->id);
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
    public function delBrand($localId): bool
    {
        $outer = $this->db->outerBrand($localId);
        $c = $this->conn;
        $resp = $c->process($c->delete("$this->catalogPath/brand/$outer"));
        if ($resp->isSuccessful() || $resp->getCode() === 404) {
            $this->db->unbindBrand($outer);
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
    public function delAllBrands(): bool
    {
        $c = $this->conn;
        $resp = $c->process($c->delete("$this->catalogPath/brand/all"));
        if ($resp->isSuccessful()) {
            $this->db->unbindAllBrands();
        }
        return $resp->isSuccessful();
    }

    #<editor-fold defaultstate="collapsed" desc="deprecated">
    /**
     * @param $localId
     * @param Brand $brand
     * @return bool
     * @throws CodeException
     * @throws ConfigurationException
     * @throws DBException
     * @deprecated Use instead stateBrand() method with the same signature
     */
    public function createBrandIfNotExists($localId, Brand $brand): bool
    {
        return $this->stateBrand($localId, $brand);
    }
    #</editor-fold>
}