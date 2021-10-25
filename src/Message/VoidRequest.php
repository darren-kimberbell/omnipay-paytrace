<?php
/**
 * PayTrace - Void a pre-authorized transaction request
 */

namespace Omnipay\PayTrace\Message;

class VoidRequest extends AbstractRequest
{

    public function getData()
    {
        $this->validate('transactionReference');

        $data = array(
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
        return parent::getEndpoint() . '/v1/transactions/void';
    }

}
