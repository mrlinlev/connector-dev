# Leveon connector
This package is useful for simplifying interaction with Leveon exchange api

## Requirement
* `PHP 8`
* `PHP` extensions: 
  * `json`
  * `curl`
  * `sqlite3`
* `composer/composer`

## Install
```
composer require leveon/connector
```

Add to your `composer.json` auto updating scripts, which will automatically migrate your project and local database on module upgrading
```
"scripts": {
    "leveon-install": "Leveon\\Connector\\Deploy\\Installer::Install", 
    "post-package-update":  [
      "@leveon-install"
    ],
    "post-package-install":  [
      "@leveon-install"
    ],
}
```

After first installation manually run 
```
composer run-script leveon-install
```