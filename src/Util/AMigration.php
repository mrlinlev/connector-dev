<?php

namespace Leveon\Connector\Util;

use Leveon\Connector\SqliteManager;

abstract class AMigration
{
    abstract public function up(SqliteManager $sqlite): void;
    public function down(SqliteManager $sqlite): void {}
}