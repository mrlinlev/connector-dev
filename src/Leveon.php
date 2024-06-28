<?php

namespace Leveon\Connector;

use Composer\Factory;
use Leveon\Connector\Exceptions\ConfigurationException;

class Leveon
{
    private static array | null $config = null;
    private static string | null $rootDir = null;

    /**
     * @return void
     * @throws ConfigurationException
     */
    private static function loadConfig(): void
    {
        if(self::$config === null) {
            self::$rootDir = dirname(Factory::getComposerFile());
            if (!file_exists(self::$rootDir . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "leveon.php")) {
                throw new ConfigurationException("Leveon configuration file does not exist");
            }
            self::$config = require self::$rootDir . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "leveon.php";
        }
    }

    /**
     * @param null $key
     * @param null $default
     * @return mixed
     * @throws ConfigurationException
     */
    public static function getConfig($key = null, $default = null): mixed
    {
        self::loadConfig();
        return $key === null ? self::$config : (self::$config[$key] ?? $default);
    }

    /**
     * @param string $key
     * @return mixed
     * @throws ConfigurationException
     */
    public static function requireConfig(string $key): mixed
    {
        self::loadConfig();
        if(isset(self::$config[$key])) return self::$config[$key];
        else throw new ConfigurationException("Leveon configuration file does not defines value for key '$key'");
    }

    /**
     * @return string
     * @throws ConfigurationException
     */
    public static function getDbPath(): string
    {
        self::loadConfig();
        if(!isset(self::$config['db'])) {
            throw new ConfigurationException("Leveon database path not defined");
        }
        return str_replace('@', self::$rootDir, self::$config["db"]);
    }
}