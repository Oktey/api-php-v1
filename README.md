![alt text](http://www.oktey.com/img/oktey150.jpg "Oktey")
# Api PHP : Version 1

[![Build Status](https://travis-ci.org/Oktey/api-php-v1.svg?branch=master)](https://travis-ci.org/Oktey/api-php-v1) ![Current Version](https://img.shields.io/badge/version-1.0.0-green.svg)

## Installation
Le plus simple est d'utiliser [composer](https://getcomposer.org/)
```bash
composer require oktey/api-php-v1
```

## Usage
```php
<?php

namespace App;

// Utiliser l'autoloader composer
require __DIR__ . '/vendor/autoload.php';

use Oktey\Api\Client;

// Identifiant API
$apiId = "abcdef";

// Clé secrète API
$apiSecret = "abcdef123456";

// Création de l'objet API
$Oktey = new Client($apiId, $apiSecret);

// Requête sur l'api
$clients = $Oktey->get('/customers/lite');

// Lecture :)
var_dump($clients->getBody());
```

