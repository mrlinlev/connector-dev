<?php

namespace Leveon\Connector\Migrations;

use Leveon\Connector\Util\AMigration;
use SQLite3;

class Migration20240520948 extends AMigration
{

    public function up(SQLite3 $sqlite): void
    {
        $sqlite->exec('CREATE TABLE brands (outer INT PRIMARY KEY, local TEXT UNIQUE)');
        $sqlite->exec('CREATE TABLE collections (outer INT PRIMARY KEY, local TEXT UNIQUE)');
        $sqlite->exec('CREATE TABLE types (outer INT PRIMARY KEY, local TEXT UNIQUE)');
        $sqlite->exec('CREATE TABLE properties (outer INT PRIMARY KEY, local TEXT UNIQUE)');
        $sqlite->exec('CREATE TABLE products (outer TEXT PRIMARY KEY, local TEXT UNIQUE)');
        $sqlite->exec('CREATE TABLE offers (outer TEXT PRIMARY KEY, local TEXT UNIQUE)');
    }

    public function down(SQLite3 $sqlite): void{
        $sqlite->exec('DROP TABLE brands');
        $sqlite->exec('DROP TABLE collections');
        $sqlite->exec('DROP TABLE types');
        $sqlite->exec('DROP TABLE properties');
        $sqlite->exec('DROP TABLE products');
        $sqlite->exec('DROP TABLE offers');
    }
}