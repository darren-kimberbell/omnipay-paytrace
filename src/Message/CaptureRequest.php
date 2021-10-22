<?php
/**
 * PayTrace - Capture a pre-authorized transaction request
 */

namespace Omnipay\PayTrace\Message;

class CaptureRequest extends AbstractRequest
{

    public function getData()
    {
        $this->validate('transactionReference', 'amount');

        $data = array(
            'amount' => $this->getAmount(),
            'integrator_id' => $this->getIntegratorId(),
            'transaction_id' => $this->getTransactionReference(),
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
        return parent::getEndpoint() . '/v1/transactions/authorization/capture';
    }

}
