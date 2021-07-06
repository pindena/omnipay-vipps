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

### Simple HTML form

```html
<form method="get">
    <input type="hidden" name="action" value="purchase">

    <label>Telefonnummer</label>
    <input type="tel" name="phone" value="99999999"><br>

    <label>Beløp (i ører)</label>
    <input type="number" name="amount"><br>

    <button>Betal med Vipps</button>
</form>
```

### Initialize gateway, authorize, purchase and redirect to Vipps

```php
use Pindena\Omnipay\Vipps\Gateway;

$gateway = new Gateway();

$gateway->initialize(array(
    'clientId'             => '',
    'clientSecret'         => '',
    'ocpSubscription'      => '',
    'merchantSerialNumber' => ''
));

$gateway->authorize();

$card = new Omnipay\Common\CreditCard(array(
    'number' => $_GET['phone']
));

$transaction = $gateway->purchase(array(
    'amount'   => $_GET['amount'],
    'currency' => 'NOK',
    'card'     => $card,
));
$response = $transaction->send();

header("Location: " . $response->getData()['url']);
```

### Vipps sends a post request to website

```php
$params = array(
    'access_token'         => $_GET['access_token'],
    'order_id'             => $_GET['order_id'],
    'transactionReference' => $_GET['order_id']
);

$response = $gateway->capture($params)->send();

echo json_encode(array());
```

### Validate the payment

```php
$response = $gateway->completePurchase()->send();
```

## Quirks

Vipps will send a `notify`-request to `[returnUrl]/v2/payments/[orderId]`.  
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
