BeSMS
=====

[![Latest Version on Packagist](https://img.shields.io/packagist/v/velostazione/besms.svg)](https://packagist.org/packages/velostazione/besms)
![test](https://github.com/velostazione/besms/actions/workflows/test.yml/badge.svg)
![PHP 8.0.10](https://img.shields.io/badge/php-8.0.10-474a8a.svg?logo=php)

The unofficial helper library to send SMS with [BeSMS.it](https://www.besms.it/)

## Installation

    composer require velostazione/besms

## Usage
See BeSMS official documentation: https://www.besms.it/documentazione_api/Documentazione_BCP_API.pdf
```php
require_once('vendor/autoload.php');

use Velostazione\BeSMS\Client;
use Velostazione\BeSMS\Api;

$client = new Client();
$besms = new BeSMS($client, '<USERNAME>', '<PASSWORD>', <API_ID>, <REPORT_TYPE>, <SENDER>);
```

### Send SMS
```php
$response = $besms->send('61491570156', 'hello world'); 
    
print_r($response);
```

#### Different sender
Beside the sender defined when instantiating the class, a different sender can be specified on the fly upon sending a message:
```php
$response = $besms->send('61491570156', 'hello world', <SENDER>); 
    
print_r($response);
```

#### Country code
Since BeSMS only accepts integer recipients, country codes in the common forms of `+93`, `0093` or `+1-684` would not be accepted.

The `send` method will take care of this automatically removing any leading plus, double zero or dash.


### View credit
```php
$response = $besms->getCredit();

print_r($response);
```

## Tests
```php
./vendor/bin/phpunit tests
```
