<?php
/**
 * PayTrace - Retrieve single transaction details from PayTrace API
 */

namespace Omnipay\PayTrace\Message;

class FetchTransactionRequest extends AbstractRequest
{

    // Include the masked card number in results
    private $include_bin = true;

    public function setIncludeBin($value) {
        $this->include_bin = $value;
    }

    public function getIncludeBin() {
        return $this->include_bin;
    }

    public function getData()
    {
        $this->validate('transactionReference');

        $data = array(
            'integrator_id' => $this->getIntegratorId(),
            'transaction_id' => $this->getTransactionReference(),
            'include_bin' => $this->getIncludeBin(),
        );

        return $data;
    }

    /**
     * Get transaction endpoint.
     *
     * @return string
     */
    protected function getEndpoint()
    {
        return parent::getEndpoint() . '/v1/transactions/export/by_id';
    }

}
