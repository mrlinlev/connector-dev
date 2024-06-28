<?php

namespace Leveon\Connector\Migrations;

use Leveon\Connector\Exceptions\CodeException;
use Leveon\Connector\Exceptions\DBException;
use Leveon\Connector\SqliteManager;
use Leveon\Connector\Util\AMigration;

class Migration20240520948 extends AMigration
{

    /**
     * @param SqliteManager $sqlite
     * @return void
     * @throws CodeException
     * @throws DBException
     */
    public function up(SqliteManager $sqlite): void
    {
        $sqlite->exec('CREATE TABLE brands (outer INT PRIMARY KEY, local TEXT UNIQUE)');
        $sqlite->exec('CREATE TABLE collections (outer INT PRIMARY KEY, brandOuter INT, local TEXT UNIQUE, brandLocal TEXT)');
        $sqlite->exec('CREATE TABLE types (outer INT PRIMARY KEY, local TEXT UNIQUE)');
        $sqlite->exec('CREATE TABLE properties (outer INT PRIMARY KEY, local TEXT UNIQUE)');
        $sqlite->exec('CREATE TABLE products (outer TEXT PRIMARY KEY, local TEXT UNIQUE)');
        $sqlite->exec('CREATE TABLE offers (outer TEXT PRIMARY KEY, productOuter TEXT, local TEXT UNIQUE, productLocal TEXT)');
    }

    /**
     * @param SqliteManager $sqlite
     * @return void
     * @throws CodeException
     * @throws DBException
     */
    public function down(SqliteManager $sqlite): void{
        $sqlite->exec('DROP TABLE brands');
        $sqlite->exec('DROP TABLE collections');
        $sqlite->exec('DROP TABLE types');
        $sqlite->exec('DROP TABLE properties');
        $sqlite->exec('DROP TABLE products');
        $sqlite->exec('DROP TABLE offers');
    }
}