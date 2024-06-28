<?php

namespace Leveon\Connector\Deploy;

use Composer\Factory;
use Leveon\Connector\SqliteManager;

class Installer
{
    protected static array $directories = [
        "config" => "config",
        "data" => "data".DIRECTORY_SEPARATOR."leveon"
    ];
    protected static string $rootDir;

    public static function Install(): void
    {
        echo "Installing leveon connector...\n";
        self::$rootDir = dirname(Factory::getComposerFile());
        self::makeDirs();
        self::copyConfig();
        self::makeDb();
        echo "Leveon connector installed\n";
    }

    public static function Migrate(): void
    {
        echo "Migrating lo latest db version...\n";
        self::makeDb();
        echo "Migrating lo latest db version completed\n";
    }

    protected static function makeDirs(): void
    {
        foreach (self::$directories as $directory) {
            $dir = self::$rootDir.DIRECTORY_SEPARATOR.$directory;
            if(!file_exists($dir)) {
                echo "Creating directory $dir\n";
                if (mkdir($dir, 0755, true) === false) {
                    die("Could not create directory $dir\n");
                } else {
                    echo "Directory $dir created\n";
                }
            } else {
                if(!is_dir($dir)) {
                    die("Path $dir assumed to be a directory, file found\n");
                }
            }
        }
    }

    protected static function getPath(string $type, string $file): string {
        if(!isset(self::$directories[$type])){
            die("Directory type $type does not exist\n");
        }
        return self::$rootDir.DIRECTORY_SEPARATOR.self::$directories[$type].DIRECTORY_SEPARATOR.$file;
    }

    protected static function localPath(string $path): string{
        return __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR.$path;
    }

    protected static function copyConfig(): void {
        if(file_exists(self::getPath("config", "leveon.config.example.php"))) return;
        echo "Copying config file...\n";
        if (copy(self::localPath("leveon.config.example.php"), self::getPath("config", "leveon.php")) === false) {
            die("Could not copy config config file\n");
        }
    }
    protected static function makeDb(): void {
        $manager = new SqliteManager();
        foreach (scandir(self::localPath("Migrations")) as $file) {
            if(preg_match('/^(Migration\d+)\._php$/', $file, $m)) {
                $manager->upMigration($m[1]);
            }
        }
        $manager->close();
    }
}