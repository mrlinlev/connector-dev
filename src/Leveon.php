<?php

namespace Leveon\Connector;

use Composer\Factory;
use Exception;

class Leveon
{
    private static array | null $config = null;
    private static string | null $rootDir = null;

    /**
     * @return void
     * @throws Exception
     */
    private static function loadConfig(): void
    {
        if(self::$config === null) {
            self::$rootDir = dirname(Factory::getComposerFile());
            if (!file_exists(self::$rootDir . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "leveon.php")) {
                throw new Exception("Leveon configuration file does not exist");
            }
            self::$config = require self::$rootDir . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "leveon.php";
        }
    }

    /**
     * @param null $key
     * @param null $default
     * @return mixed
     * @throws Exception
     */
    public static function getConfig($key = null, $default = null): mixed
    {
        self::loadConfig();
        return $key === null ? self::$config : (self::$config[$key] ?? $default);
    }

    /**
     * @throws Exception
     */
    public static function getDbPath(): string
    {
        self::loadConfig();
        if(!isset(self::$config['db'])) {
            throw new Exception("Leveon database path not defined");
        }
        return str_replace('@', self::$rootDir, self::$config["db"]);
    }
}