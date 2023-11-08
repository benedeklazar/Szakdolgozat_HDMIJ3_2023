#!/bin/sh

#INFO: run this file to setup your app, if you are using XAMPP.

#command to run this file:
#powershell -ExecutionPolicy Bypass -File start.ps1

Write-Host "`n[1/6]: Installing Composer...`n" -ForegroundColor Blue
if ([System.IO.File]::Exists('vendor/autoload.php')) 
{Write-Host "Info: Composer already installed!" -ForegroundColor Green}
else {composer install}

Write-Host "`n[2/6]: Creating .env file...`n" -ForegroundColor Blue
if ([System.IO.File]::Exists('.env'))
{Write-Host "Info: .env file already exists!" -ForegroundColor Green}
else {cp .env_2.example .env}

Write-Host "`n[3/6]: Creating app key...`n" -ForegroundColor Blue
php artisan key:generate

Write-Host "`n[4/6]: Creating database...`n" -ForegroundColor Blue
php init_db.php

Write-Host "`n[5/6]: Creating database tables...`n" -ForegroundColor Blue
php artisan migrate

Write-Host "`n[6/6]: Running server at 8000 port...`n" -ForegroundColor Blue
php artisan serve --port=8000 --host=0.0.0.0 --env=.env
