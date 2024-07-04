<?php

namespace Leveon\Connector\Traits\Requests;

use Leveon\Connector\Exceptions\CodeException;
use Leveon\Connector\Exceptions\ConfigurationException;
use Leveon\Connector\Exceptions\DBException;
use Leveon\Connector\Models\Offer;

trait Offers
{
    /**
     * Создать товарное предложение, если оно не создано
     * @param $productLocalId
     * @param $localId
     * @param Offer $model
     * @return bool
     * @throws CodeException
     * @throws ConfigurationException
     * @throws DBException
     */
    public function stateOffer($productLocalId, $localId, Offer $model): bool
    {
        $outer = $this->db->outerOffer($localId);
        if ($outer !== null) return true;
        $outerProduct = $this->db->outerProduct($productLocalId);
        $c = $this->conn;
        $payload = $outerProduct !== null ? $model->toJSON(['article', 'image', 'properties']) : $model->toJSON();
        $resp = $c->process(
            $c->post("$this->catalogPath/".($outerProduct? "existed-product/$outerProduct" : 'new-product')."/offer", $payload)
        );
        if ($resp->isSuccessful()) {
            $this->db->bindOffer($localId, $resp->json()->id, $productLocalId, $resp->json()->product);
            if($outerProduct===null){
                $this->db->bindProduct($productLocalId, $resp->json()->product);
            }
            return true;
        }
        return false;
    }

    /**
     * @param $productLocalId
     * @param $localId
     * @param Offer $model
     * @return bool
     * @throws CodeException
     * @throws ConfigurationException
     * @throws DBException
     */
    public function upOffer($productLocalId, $localId, Offer $model): bool
    {
        $outer = $this->db->outerOffer($localId);
        $outerProduct = $this->db->outerProduct($productLocalId);
        $c = $this->conn;
        if ($outerProduct===null) {
            $resp = $c->process($c->post("$this->catalogPath/new-product/offer", $model->toJSON()));
        } elseif ($outer === null) {
            $resp = $c->process($c->post("$this->catalogPath/existed-product/$outerProduct/offer", $model->toJSON(['article', 'image', 'properties'])));
        } else {
            $resp = $c->process($c->patch("$this->catalogPath/offer/$outer", $model->toJSON(['article', 'image', 'properties'])));
        }
        if ($resp->isSuccessful() && $outer===null) {
            $this->db->bindOffer($localId, $resp->json()->id, $productLocalId, $resp->json()->product);
            if($outerProduct===null){
                $this->db->bindProduct($productLocalId, $resp->json()->product);
            }
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
    public function delOffer($localId): bool
    {
        $outer = $this->db->outerOffer($localId);
        $c = $this->conn;
        $resp = $c->process($c->delete("$this->catalogPath/offer/$outer"));
        if ($resp->isSuccessful()) {
            $this->db->unbindOffer($outer);
            if (isset($resp->json()->product)) {
                $this->db->unbindProduct($resp->json()->product);
            }
            return true;
        } else if($resp->getCode() === 404) {
            $this->db->unbindOffer($outer);
            return true;
        }
        return false;
    }

    #<editor-fold defaultstate="collapsed" desc="deprecated">
    /**
     * @param $brandLocalId
     * @param $localId
     * @param Offer $model
     * @return bool
     * @throws CodeException
     * @throws ConfigurationException
     * @throws DBException
     * @deprecated Use instead stateOffer() method with the same signature
     */
    public function createOfferIfNotExists($brandLocalId, $localId, Offer $model): bool
    {
        return $this->stateOffer($brandLocalId, $localId, $model);
    }
    #</editor-fold>


}