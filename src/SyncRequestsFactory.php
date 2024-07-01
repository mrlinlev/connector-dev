<?php

namespace Leveon\Connector;

use Leveon\Connector\Exceptions\CodeException;
use Leveon\Connector\Exceptions\ConfigurationException;
use Leveon\Connector\Models\AmountsPacker;
use Leveon\Connector\Models\PricesPacker;
use Leveon\Connector\Models\RelationsPacker;
use Leveon\Connector\Traits\Requests\Brands;
use Leveon\Connector\Traits\Requests\BulkSync;
use Leveon\Connector\Traits\Requests\Collections;
use Leveon\Connector\Traits\Requests\Offers;
use Leveon\Connector\Traits\Requests\Products;
use Leveon\Connector\Traits\Requests\Properties;
use Leveon\Connector\Traits\Requests\Types;

class SyncRequestsFactory
{
    use Offers;
    use Products;
    use Brands;
    use Types;
    use Collections;
    use Properties;
    use BulkSync;
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