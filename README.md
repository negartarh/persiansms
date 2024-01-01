# Persian SMS PHP SDK

Persian SMS PHP SDK

Install:
```shell
composer require negartarh/persiansms:dev-master
```

Usage:

```php
use Negartarh\Persiansms\PersianSms;

require_once __DIR__ . '/../vendor/autoload.php';

$config = (array) [
    'Username'    => '',
    'Password'    => '',
    'API'         => '',
    'FROM'        => '',
    'FLASH'       => '',
    'Internation' => '',
    'DATE'        => '',
    ];

$user    = (string) '';
$pass    = (string) '';
$from    = (string) '';
$text    = (string) '';
$numbers = (array) [''];

$sms = ( new PersianSms($config) )
    ->withUser( $user )
    ->withPass( $pass )
    ->from( $from )
    ->send( $text, $numbers );

var_dump($sms);
```
or user helper function
```php
$sms = persian_sms($config)->send( $text, $numbers );
```