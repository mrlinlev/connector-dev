<?php

namespace Leveon\Connector\Deploy;

use Composer\Factory;

class Installer
{
    public static function Install(): void
    {
        echo "Installing...\n";
        /*$projectPath = dirname(Factory::getComposerFile());
        $configsPath = $projectPath.DIRECTORY_SEPARATOR.'config';
        if(!file_exists($configsPath)) mkdir($configsPath);
        $leveonConfigFile = $configsPath.DIRECTORY_SEPARATOR.'leveon.config.php';
        if(!file_exists($leveonConfigFile))
            copy(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR.'leveon.config.example.php', $leveonConfigFile);*/
        echo "Installed\n";
    }

    public static function Update(): void{
        echo "Updating...\n";
        echo "Updated\n";
    }

    public static function PkgInstall(): void{
        echo "Pkg installing\n";
        echo "Pkg installed\n";
    }

    public static function PkgUpdate(): void{
        echo "Pkg updating...\n";
        echo "Pkg updated\n";
    }
}