# Filemanager tool for Laravel Nova 4

A Filemanager Tool and Field for Laravel Nova 4

##### Filemanager Tool preview

![FileManager Tool](https://user-images.githubusercontent.com/42798230/44862985-d3d57b80-ac73-11e8-9169-2e76a3584ea4.gif)

##### Filemanager Field preview

![FileManager Field](https://user-images.githubusercontent.com/42798230/44864362-5f9cd700-ac77-11e8-9e0f-330d18a81598.gif)

## Clevyr Nova Filemanager

This package will be maintained and updated by Clevyr going forward. This package is currently compatible with Nova 3 and Laravel 9.

### Docs
Forked from https://github.com/stepanenko3/nova-filemanager, which is a fork of https://github.
com/InfinetyEs/Nova-Filemanager/

## Installation
```
composer install clevyr/nova-filemanager
```
then
```
php artisan vendor:publish --tag=filemanager-config
```
Also, you must register the tool with Nova. This is typically done in the tools method of the NovaServiceProvider.
```
// in app/Providers/NovaServiceProvider.php

public function tools()
{
    return [
        // ...
        new \Clevyr\Filemanager\FilemanagerTool(),
    ];
}
```
