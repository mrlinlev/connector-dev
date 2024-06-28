<?php

namespace Leveon\Connector;

use Leveon\Connector\Exceptions\CodeException;
use Leveon\Connector\Exceptions\ConfigurationException;
use Leveon\Connector\Exceptions\DBException;
use Leveon\Connector\Models\AModel;
use Leveon\Connector\Models\AmountsPacker;
use Leveon\Connector\Models\PricesPacker;
use Leveon\Connector\Models\RelationsPacker;

class SyncRequestsFactory
{

    private int $catalog;
    private string $catalogPath;
    private string $basePath;
    private Connector $conn;
    private SqliteManager $db;

    /**
     * @param $catalog
     * @throws ConfigurationException
     */
    public function __construct($catalog = null)
    {
        if($catalog!==null){
            $this->catalog = (int)$catalog;
        }else{
            $catalog = Leveon::getConfig('catalog');
            if(!is_int($catalog) || $catalog <= 0){
                throw new ConfigurationException('Catalog not provided and in configuration not provided or has wrong format');
            }
        }
        $this->conn = new Connector();
        $this->basePath = '/api';
        $this->catalogPath = "$this->basePath/catalog/$catalog";
        $this->db = new SqliteManager();
    }

    /**
     * @param PricesPacker $packer
     * @return bool
     * @throws ConfigurationException
     * @throws CodeException
     */
    public function syncPrices(PricesPacker $packer): bool
    {
        $send = $packer->toJSON(['delete' => true]);
        if ($send !== null) {
            $c = $this->conn;
            $resp = $c->process(
                $c->delete("{$this->catalogPath}/offer/prices", $send)
            );
            if ($resp->isFailed()) return false;
        }
        $send = $packer->toJSON(['delete' => false]);
        if ($send !== null) {
            $c = $this->conn;
            $resp = $c->process(
                $c->patch("{$this->catalogPath}/offer/prices", $send)
            );
            if ($resp->isFailed()) return false;
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
        $send = $packer->toJSON(['delete' => true]);
        if ($send !== null) {
            $c = $this->conn;
            $resp = $c->process(
                $c->delete("{$this->catalogPath}/offer/amounts", $send)
            );
            if ($resp->isFailed()) return false;
        }
        $send = $packer->toJSON(['delete' => false]);
        if ($send !== null) {
            $c = $this->conn;
            $resp = $c->process(
                $c->patch("{$this->catalogPath}/offer/amounts", $send)
            );
            if ($resp->isFailed()) return false;
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
        $send = $packer->toJSON(['old']);
        if ($send !== null) {
            $c = $this->conn;
            $resp = $c->process(
                $c->delete("{$this->catalogPath}/product-relations", $send)
            );
            if ($resp->isFailed()) return false;
        }
        $send = $packer->toJSON(['new']);
        if ($send !== null) {
            $c = $this->conn;
            $resp = $c->process(
                $c->post("{$this->catalogPath}/product-relations", $send)
            );
            if ($resp->isFailed()) return false;
        }
        return true;
    }

    /**
     * @param $type
     * @param $path
     * @param $localId
     * @param $model
     * @return bool
     * @throws CodeException
     * @throws ConfigurationException
     * @throws DBException
     */
    public function createInstanceIfNotExists($type, $path, $localId, $model): bool
    {
        $outer = $this->db->outerByLocal($type, $localId);
        if ($outer !== null) return true;
        $c = $this->conn;
        $send = $model instanceof AModel ? $model->toJSON() : $model;
        $resp = $c->process(
            $c->post("$this->catalogPath/$path", $send)
        );
        if ($resp->isSuccessful()) {
            $outerId = $resp->json()->id;
            $this->db->bind($type, $localId, $outerId);
            return true;
        }
        return false;
    }

    /**
     * @param $type
     * @param $path
     * @param $localId
     * @param $model
     * @return bool
     * @throws CodeException
     * @throws ConfigurationException
     * @throws DBException
     */
    public function upInstance($type, $path, $localId, $model): bool
    {
        $c = $this->conn;
        $outer = $this->db->outerByLocal($type, $localId);
        $send = $model instanceof AModel ? $model->toJSON() : $model;
        if ($outer !== null) {
            $resp = $c->process(
                $c->patch("{$this->catalogPath}/$path/$outer/model", $send)
            );
        } else {
            $resp = $c->process(
                $c->post("$this->catalogPath/$path/model", $send)
            );
            if ($resp->isSuccessful()) {
                $outerId = $resp->json()->id;
                $this->db->bind($type, $localId, $outerId);
            }
        }
        return $resp->isSuccessful();
    }

    /**
     * @param $type
     * @param $path
     * @param $localId
     * @return bool
     * @throws CodeException
     * @throws ConfigurationException
     * @throws DBException
     */
    public function delInstance($type, $path, $localId): bool
    {
        $outer = $this->db->outerByLocal($type, $localId);
        if ($outer === null) return true;
        $c = $this->conn;
        $resp = $c->process(
            $c->delete("{$this->catalogPath}/{$path}/{$outer}")
        );
        if ($resp->isSuccessful() || $resp->getCode() === 404) {
            $this->db->unbind($type, $localId);
            return true;
        }
        return false;
    }

    /**
     * @param $type
     * @param $path
     * @param $outer
     * @return bool
     * @throws CodeException
     * @throws ConfigurationException
     * @throws DBException
     */
    public function delInstanceOuter($type, $path, $outer): bool
    {
        $c = $this->conn;
        $resp = $c->process(
            $c->delete("{$this->catalogPath}/{$path}/{$outer}")
        );
        if ($resp->isSuccessful() || $resp->getCode() === 404) {
            $this->db->unbindOuter($type, $outer);
            return true;
        }
        return false;
    }

    /**
     * @param $type
     * @param $path
     * @return void
     * @throws CodeException
     * @throws ConfigurationException
     * @throws DBException
     */
    public function delAllInstances($type, $path): void
    {
        $list = $this->db->all($type);
        foreach ($list as $item) {
            $this->delInstanceOuter($type, $path, $item['outer']);
        }
    }

    public function upBrand($localId, $model): bool
    {
        return $this->upInstance('brand', 'brand', $localId, $model);
    }

    public function createBrandIfNotExists($localId, $model): bool
    {
        return $this->createInstanceIfNotExists('brand', 'brand', $localId, $model);
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
        return $this->delInstance('brand', 'brand', $localId);
    }

    public function delAllBrands(): void
    {
        $this->delAllInstances('brand', 'brand');
    }

    public function createPropertyIfNotExists($localId, $model): bool
    {
        return $this->createInstanceIfNotExists('property', 'properties', $localId, $model);
    }

    public function upProperty($localId, $model): bool
    {
        return $this->upInstance('property', 'properties', $localId, $model);
    }

    public function delProperty($localId): bool
    {
        return $this->delInstance('property', 'properties', $localId);
    }

    public function createProductTypeIfNotExists($localId, $model): bool
    {
        return $this->createInstanceIfNotExists('type', 'type', $localId, $model);
    }

    public function upProductType($localId, $model): bool
    {
        return $this->upInstance('type', 'type', $localId, $model);
    }

    public function delProductType($localId): bool
    {
        return $this->delInstance('type', 'type', $localId);
    }

    public function createCollectionIfNotExists($localBrandId, $localId, $model): bool
    {
        $outerBrandId = $this->db->outerByLocal('brand', $localBrandId);
        return $this->createInstanceIfNotExists('collection', "brand/{$outerBrandId}/collection", $localId, $model);
    }

    public function upCollection($localBrandId, $localId, $model): bool
    {
        $outerBrandId = $this->db->outerByLocal('brand', $localBrandId);
        return $this->upInstance('collection', "brand/{$outerBrandId}/collection", $localId, $model);
    }

    public function delCollection($localBrandId, $localId): bool
    {
        $outerBrandId = $this->db->outerByLocal('brand', $localBrandId);
        return $this->delInstance('collection', "brand/{$outerBrandId}/collection", $localId);
    }

    public function upProduct($localId, $model): bool
    {
        return $this->upInstance('product', 'products', $localId, $model);
    }

    public function delProduct($localId): bool
    {
        return $this->delInstance('product', 'products', $localId);
    }

    public function delAllProducts(): void
    {
        $this->delAllInstances('product', 'products');
    }

    /**
     * @param $productLocalId
     * @param $localId
     * @param $model
     * @return bool
     * @throws CodeException
     * @throws ConfigurationException
     * @throws DBException
     */
    public function createOfferIfNotExists($productLocalId, $localId, $model): bool
    {
        /*$outer = $this->db->outerByLocal('offer', $localId);
        if ($outer !== null) return true;
        $outerProduct = $this->db->outerByLocal('product', $productLocalId);
        $c = $this->conn;
        $send = $model instanceof AModel ? $model->toJSON() : $model;
        $resp = $c->process(
            $c->post("$this->catalogPath/$path", $send)
        );
        if ($resp->isSuccessful()) {
            $outerId = $resp->json()->id;
            $this->db->bind($type, $localId, $outerId);
            return true;
        }
        return false;*/
        return $this->createInstanceIfNotExists('offer', 'offer', $localId, $model);
    }

    public function upOffer($localId, $model): bool
    {
        return $this->upInstance('offer', 'offer', $localId, $model);
    }

    public function delOffer($localId): bool
    {
        return $this->delInstance('offer', 'offer', $localId);
    }

    public function finish(): void
    {
        $this->db->close();
    }

    public function getCatalog(): int
    {
        return $this->catalog;
    }

    public function getCatalogPath(): string
    {
        return $this->catalogPath;
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function getConn(): Connector
    {
        return $this->conn;
    }

    public function getDb(): SqliteManager
    {
        return $this->db;
    }

}