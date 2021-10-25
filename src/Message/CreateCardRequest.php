<?php
/**
 * PayTrace Create Card Request (Customer Profile)
 */

namespace Omnipay\PayTrace\Message;

class CreateCardRequest extends AbstractRequest
{
    public function getData()
    {
        // Generate a unique customer ID
        $customerId = $this->uniqueId();

        // Test if number is valid CC, or use encrypted
        $cardField = ($this->checkCC($this->getCard()->getNumber())) ? "number" : "encrypted_number";

        $data = array(
            'customer_id' => $customerId,
            'integrator_id' => $this->getIntegratorId(),
            'email' => $this->getCard()->getEmail(),
            'phone' => $this->getCard()->getPhone(),
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
        return parent::getEndpoint() . '/v1/customer/create';
    }
}
