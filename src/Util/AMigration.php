<?php


namespace Leveon\Connector\Util;


use SQLite3;

abstract class AMigration
{
    public function up(SQLite3 $sqlite){}
    public function down(SQLite3 $sqlite){}
}