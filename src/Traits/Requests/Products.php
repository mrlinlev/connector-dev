<?php

namespace Leveon\Connector\Traits\Requests;

use Leveon\Connector\Exceptions\CodeException;
use Leveon\Connector\Exceptions\ConfigurationException;
use Leveon\Connector\Exceptions\DBException;
use Leveon\Connector\Models\Product;

trait Products
{
    /**
     * Обновление параметров товара
     * @param $localId
     * @param Product $model
     * @return bool
     * @throws CodeException
     * @throws ConfigurationException
     * @throws DBException
     */
    public function updateProduct($localId, Product $model): bool
    {
        $outer = $this->db->outerProduct($localId);
        $c = $this->conn;
        $resp = $c->process($c->patch("$this->catalogPath/products/$outer", $model->toJSON()));
        return $resp->isSuccessful();
    }

    /**
     * @param $localId
     * @return bool
     * @throws CodeException
     * @throws ConfigurationException
     * @throws DBException
     */
    public function delProduct($localId): bool
    {
        $outer = $this->db->outerProduct($localId);
        $c = $this->conn;
        $resp = $c->process($c->delete("$this->catalogPath/products/$outer"));
        if ($resp->isSuccessful() || $resp->getCode() === 404) {
            $this->db->unbindProduct($outer);
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
    public function delAllProducts(): bool
    {
        $c = $this->conn;
        $resp = $c->process($c->delete("$this->catalogPath/products/all"));
        if ($resp->isSuccessful()) {
            $this->db->unbindAllProducts();
        }
        return $resp->isSuccessful();
    }
}