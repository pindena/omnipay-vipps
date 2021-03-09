# Vipps - Omnipay

[Packagist](https://packagist.org/packages/coretrekstein/vippsomnipay)
[GitHub](https://github.com/coretrekstein/vippsomnipay)

## Install

```
composer require coretrekstein/vippsomnipay
```

## Simple html form for mobile number and amount

```html
<form method="get">
    <input type="hidden" name="action" value="purchase">

    <label>Telefonnummer</label>
    <input type="tel" name="phone" value="91236172"><br>

    <label>Beløp (i ører)</label>
    <input type="number" name="amount"><br>

    <button>Betal med Vipps</button>
</form>
```

## Initialize gateway, authorize, purchase and redirect to Vipps

```php
use CoreTrekStein\VippsOmnipay;

$gateway = new VippsOmnipay\Gateway();

$gateway->authorize());

$card = new CreditCard(array(
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

## Vipps sends a post request to website

```php
$params = array(
    'access_token'         => $_GET['access_token'],
    'order_id'             => $_GET['order_id'],
    'transactionReference' => $_GET['order_id']
);

$response = $gateway->capture($params)->send();

echo json_encode(array());
```

## Validate the payment

```php
$response = $gateway->completePurchase()->send();
```
