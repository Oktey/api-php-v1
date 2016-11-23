# Api Oktey PHP : Version 1

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

// Identifiant revendeur
$key = "abcdef";

// Clé secrète revendeur
$secret = "abcdef123456";

// Création de l'objet API
$Api = new Client($key, $secret);

// Requête sur l'api
$clients = $Api->get('/customers/lite');

// Lecture :)
var_dump($clients->getBody());
```
