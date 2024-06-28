<?php

namespace Leveon\Connector\Migrations;

use Leveon\Connector\Util\AMigration;
use SQLite3;

class Migration20240521136 extends AMigration{

    public function up(SQLite3 $sqlite): void
    {
        $sqlite->query("CREATE TABLE versions (thread TEXT PRIMARY KEY, version TEXT UNIQUE)");
    }

    public function down(SQLite3 $sqlite): void
    {
        $sqlite->query("DROP TABLE IF EXISTS versions");
    }

}