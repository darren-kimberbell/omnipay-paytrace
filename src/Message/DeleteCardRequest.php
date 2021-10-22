<?php
/**
 * PayTrace Delete Card Request (Customer Profile)
 */

namespace Omnipay\PayTrace\Message;

class DeleteCardRequest extends AbstractRequest
{
    public function getData()
    {
        $data = array(
            'customer_id' => $this->getCardReference(),
            'integrator_id' => $this->getIntegratorId(),
        );

        return $data;
    }

    protected function getEndpoint()
    {
        return parent::getEndpoint() . '/v1/customer/delete';
    }
}
