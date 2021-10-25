<?php
/**
 * PayTrace Purchase Transaction Request
 */

namespace Omnipay\PayTrace\Message;

class PurchaseRequest extends AbstractRequest
{

    public function getData()
    {
        // validation
        $this->validate('amount');

        // check if CVV code is encrypted
        $CVV = $this->getCard()->getCvv();
        $cvvField = (is_numeric($CVV) && (intval($CVV) < 9999)) ? "csc" : "encrypted_csc";
        // check if CC Num is encrypted
        $cardField = ($this->checkCC($this->getCard()->getNumber())) ? "number" : "encrypted_number";

        $data = array(
            'amount' => $this->getAmount(),
            'integrator_id' => $this->getIntegratorId(),
            'invoice_id' => $this->getTransactionId(),
            'description' => $this->getDescription(),
            'email' => $this->getCard()->getEmail(),
            'phone' => $this->getCard()->getPhone(),
            $cvvField => $this->getCard()->getCvv(),
        );

        // saved card
        if ($this->getCardReference()) {
            $data['customer_id'] = $this->getCardReference();

        // one time card info
        } else {
            $data['credit_card'] = array(
                $cardField => $this->getCard()->getNumber(),
                'expiration_month' => $this->getCard()->getExpiryMonth(),
                'expiration_year' => $this->getCard()->getExpiryYear(),
            );
            $data['billing_address'] = array(
                'name' => $this->getCard()->getFirstName()." ".$this->getCard()->getLastName(),
                'street_address' => $this->getCard()->getAddress1(),
                'street_address2' => $this->getCard()->getAddress2(),
                'city' => $this->getCard()->getCity(),
                'state' => $this->getCard()->getState(),
                'zip' => $this->getCard()->getPostcode(),
                'country' => strtoupper($this->getCard()->getCountry()),
            );
        }

        return $data;
    }

    /**
     * Get transaction endpoint.
     *
     * @return string
     */
    protected function getEndpoint()
    {
        if ($this->getCardReference()) {
            return parent::getEndpoint() . '/v1/transactions/sale/by_customer';
        } else {
            return parent::getEndpoint() . '/v1/transactions/sale/keyed';
        }
    }

}
