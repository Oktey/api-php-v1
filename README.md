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
use Exception;

// Display errors for dev
ini_set('display_errors', 1); ini_set('html_errors', 1);

// Création de l'objet API
$Api = new Client('apiId', 'apiSecret');

try {
    // Requête sur l'api
    $response = $Api->get('/customers/lite');

    if ($response->success()) {
        // Récupération des données
        $customers = $response->getData();

        // affichage ;)
        var_dump($customers);
    } else {
        trigger_error(sprintf('Error %d : %s', $response->getStatus(), $response->getMessageError()), E_USER_WARNING);
    }
} catch(Exception $e) {
    // Oops !!!
    trigger_error(sprintf('Api Exception %d : %s', $e->getCode(), $e->getMessage()), E_USER_WARNING);
}


```

