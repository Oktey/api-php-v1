![alt text](https://www.oktey.com/assets/img/svg/logo-oktey.svg "Oktey")
# Api PHP : Version 1

[![Packagist](https://img.shields.io/packagist/v/oktey/api-php-v1.svg?style=flat-square)](https://packagist.org/packages/oktey/api-php-v1) [![Travis](https://img.shields.io/travis/rust-lang/rust.svg?style=flat-square)](https://travis-ci.org/Oktey/api-php-v1)


## Installation
Vu les dépendances utilisées, il est impératif d'utiliser [composer](https://getcomposer.org/)
```bash
# Si composer n'est pas encore installé
curl -sS https://getcomposer.org/installer | php
mv composer.phar composer

php composer require oktey/api-php-v1
```

## Fonctionnalités
Voici les fonctionnalités de l'API ainsi que leurs liens de documentation

**en lecture**
* [[recherche d'un client|url-list#rechercher-un-client]]
* [[liste des clients|url-list#liste-de-mes-clients]]
* [[détail d'un client|url-list#détail-dun-client]]

**en écriture**
* [[création d'un client|url-list#créer-un-compte-client]]
* [[création d'une commande de test|url-list#créer-une-commande-de-test]]
* [[création d'un domaine|url-list#créer-un-domaine]]


## Usage
```php
<?php
namespace App;

// Utiliser l'autoloader composer
require __DIR__ . '/vendor/autoload.php';

use Oktey\Api\Client;
use Exception;

// Afficher les erreurs en mode développement
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

