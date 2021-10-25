<?php
require 'vendor/autoload.php';

use Omnipay\Omnipay;

// Setup payment gateway
$gateway = Omnipay::create('PayTrace');
$gateway->setUsername('kimberbell-sandbox-api');
$gateway->setPassword('Kimber2021bell');
$gateway->setIntegratorId('962362GLn3E3');

// Valid for fields
//     'firstName',
//     'lastName',
//     'number',
//     'expiryMonth',
//     'expiryYear',
//     'startMonth',
//     'startYear',
//     'cvv',
//     'issueNumber',
//     'type',
//     'billingAddress1',
//     'billingAddress2',
//     'billingCity',
//     'billingPostcode',
//     'billingState',
//     'billingCountry',
//     'billingPhone',
//     'shippingAddress1',
//     'shippingAddress2',
//     'shippingCity',
//     'shippingPostcode',
//     'shippingState',
//     'shippingCountry',
//     'shippingPhone',
//     'company',
//     'email'


//Example card data
$cardData = array(
    'firstName' => 'Darren',
    'lastName'  => 'Nay',
    'number' => '4012000098765439',
    'expiryMonth' => '12',
    'expiryYear' => '2024',
    'cvv' => '999',
    'billingAddress1' => '2442 Wolfpack Way',
    'billingCity' => 'North Logan',
    'billingState' => 'UT',
    'billingPostcode' => '84341',
    'billingCountry' => 'US',
    'email' => 'darren@kimberbell.com',
    'phone' => '435-603-0589',
);

//$response = $gateway->createCard(['card'=>$formData])->send();

// existing customer in sandbox - 770806453113a

// $formData = array(
//     'amount' => 2.00,
//     'transactionId' => "test-".time(),
//     'cardReference' => "770806453113a",
//     'description' => "Development Test Transaction w/Customer",
//     'card' => $cardData
// );

//$response = $gateway->authorize($formData)->send();
//$response = $gateway->purchase($formData)->send();

// $formData = array(
//     'amount' => 1.00,
//     'transactionReference' => '423730109',
// );
// $response = $gateway->capture($formData)->send();

$formData = array(
    'transactionReference' => '423935670',
);
$response = $gateway->void($formData)->send();


// Process response
if ($response->isSuccessful()) {

    // Payment was successful
    //print_r($response);
    print_r("Transaction Successfull!");
    print_r($response->getData());

} else {

    // Payment failed
    print_r($response->getData());
    print_r("----------------------------\n");
    print_r($response->getMessage());
}


?>
