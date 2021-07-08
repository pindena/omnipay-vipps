# Omnipay: Vipps

**Vipps driver for the Omnipay PHP payment processing library**

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP. This package implements Vipps support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply require `league/omnipay` and `pindena/omnipay-vipps` with Composer:

```
composer require league/omnipay pindena/omnipay-vipps
```


## Basic Usage

The following gateways are provided by this package:

* Vipps (Vipps Ecomm Checkout)

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository and the [Vipps documentation](https://vipps.no/developers-documentation/). 

### Initialize gateway, authorize and redirect to Vipps

```php
use Pindena\Omnipay\Vipps\Gateway;

$gateway = new Gateway();

$gateway->initialize([
    'clientId'             => '',
    'clientSecret'         => '',
    'ocpSubscription'      => '',
    'merchantSerialNumber' => '',
]);

$response = $gateway->authorize([
    'amount'      => '10.00',
    'currency'    => 'NOK',
    'description' => 'This is a test transaction',
    'phone'       => $_POST['phone'],
    'returnUrl'   => $fallbackUrl,
    'notifyUrl'   => $callbackPrefix,
])->send();

if ($response->isRedirect()) {
    $response->redirect();
}
```

### Capture the authorized amount

```php
$response = $gateway->capture([
    'amount'               => $amount,
    'description'          => 'This is a test transaction',
    'transactionReference' => $transactionReference,
])->send();
```

### Get the transaction details

```php
$response = $gateway->completeAuthorize(['transactionReference' => $transactionReference])->send();
```

### Troubleshooting headers

Vipps requires partners and platforms to send special headers on all requests. This can be achieved by adding headers in the initialize options or on each request.

```php
$gateway->initialize([
    'clientId'             => '',
    'clientSecret'         => '',
    'ocpSubscription'      => '',
    'merchantSerialNumber' => '',
    'headers'              => [
        'Vipps-System-Name' => 'System name',
        'Vipps-System-Version' => 'v1.0',
    ],
]);
```

## Quirks

Vipps will send a `notify`-request to `[notifyUrl]/v2/payments/[orderId]`.
For Laravel this means you will need to register a `POST` route in `web.php` which listens on `/v2/payments/{orderId}` for handling payment notifications from Vipps.

## Out Of Scope

Omnipay does not cover recurring payments or billing agreements, and so those features are not included in this package. Extensions to this gateway are always welcome.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/pindena/omnipay-vipps/issues),
or better yet, fork the library and submit a pull request.
