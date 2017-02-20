![alt text](https://www.oktey.com/assets/img/svg/logo-oktey.svg "Oktey")
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

// Création de l'objet API
$Api = new Client('apiId', 'apiSecret');

// Requête sur l'api
$response = $Api->get('/customers/lite');

if ($response->success()) {
    // Récupération des données
    $customers = $response->getData();

    // affichage ;)
    var_dump($customers);
}

```

