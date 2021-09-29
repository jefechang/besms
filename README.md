BeSMS
=====

![test](https://github.com/velogest/besms/actions/workflows/test.yml/badge.svg)

The unofficial helper library to send SMS with [BeSMS](https://www.besms.it/)

## Installation

    composer require velostazione/besms

## Usage
See BeSMS official documentation: https://www.besms.it/documentazione_api/Documentazione_BCP_API.pdf
```php
require_once('vendor/autoload.php');

use Velostazione\BeSMS\Client;
use Velostazione\BeSMS\Api;

$client = new Client();
$besms = new BeSMS($client, 'YOUR_USERNAME', 'YOUR_PASSWORD', <API_ID>, <REPORT_TYPE>);
```

### Send SMS
```php
$response = $besms->send('61491570156', 'hello world', <SENDER>); 
    
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
