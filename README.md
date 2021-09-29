BeSMS
=====

![test](https://github.com/velogest/besms/actions/workflows/test.yml/badge.svg)

The unoffical helper library to send SMS with [BeSMS](https://www.besms.it/)

## Installation

    composer require velostazione/besms

## Usage
```php
require_once('vendor/autoload.php');

use Velostazione\BeSMS\Client;
use Velostazione\BeSMS\Api;

$client = new Client();
$besms = new BeSMS($client, 'YOUR_USERNAME', 'YOUR_PASSWORD');
```

### Send SMS
```php
$response = $besms->send('61491570156', 'hello world'); 
    
print_r($response);
```

### View credit
```php
$response = $besms->getCredit();

print_r($response);
```

## Tests
```php
./vendor/bin/phpunit tests
```
