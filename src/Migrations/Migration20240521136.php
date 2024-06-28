<?php

namespace Leveon\Connector\Migrations;

use Leveon\Connector\Exceptions\CodeException;
use Leveon\Connector\Exceptions\DBException;
use Leveon\Connector\SqliteManager;
use Leveon\Connector\Util\AMigration;

class Migration20240521136 extends AMigration{

    /**
     * @param SqliteManager $sqlite
     * @return void
     * @throws CodeException
     * @throws DBException
     */
    public function up(SqliteManager $sqlite): void
    {
        $sqlite->exec("CREATE TABLE versions (thread TEXT PRIMARY KEY, version TEXT UNIQUE)");
    }

    /**
     * @param SqliteManager $sqlite
     * @return void
     * @throws CodeException
     * @throws DBException
     */
    public function down(SqliteManager $sqlite): void
    {
        $sqlite->exec("DROP TABLE IF EXISTS versions");
    }

}