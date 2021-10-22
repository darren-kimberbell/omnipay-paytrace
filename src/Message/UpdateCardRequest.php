<?php
/**
 * PayTrace Update Card Request (Customer Profile)
 */

namespace Omnipay\PayTrace\Message;

class UpdateCardRequest extends AbstractRequest
{
    public function getData()
    {
        // Test if number is valid CC, or use encrypted
        $cardField = ($this->checkCC($this->getCard()->getNumber())) ? "number" : "encrypted_number";

        $data = array(
            'customer_id' => $this->getCardReference(),
            'integrator_id' => $this->getIntegratorId(),
            'credit_card' => array(
                $cardField => $this->getCard()->getNumber(),
                'expiration_month' => $this->getCard()->getExpiryMonth(),
                'expiration_year' => $this->getCard()->getExpiryYear(),
            ),
            'billing_address' => array(
                'name' => $this->getCard()->getFirstName()." ".$this->getCard()->getLastName(),
                'street_address' => $this->getCard()->getAddress1(),
                'street_address2' => $this->getCard()->getAddress2(),
                'city' => $this->getCard()->getCity(),
                'state' => $this->getCard()->getState(),
                'zip' => $this->getCard()->getPostcode(),
                'country' => strtoupper($this->getCard()->getCountry()),
            )
        );

        return $data;
    }

    protected function getEndpoint()
    {
        return parent::getEndpoint() . '/v1/customer/update';
    }
}
