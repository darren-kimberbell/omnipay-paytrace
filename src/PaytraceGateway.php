<?php

namespace Omnipay\PayTrace;

use Omnipay\Common\AbstractGateway;

/**
 * PayTrace Gateway
 */
class PayTraceGateway extends AbstractGateway
{
    public function getName()
    {
        return 'PayTrace';
    }

    public function getDefaultParameters()
    {
        return array(
            'key' => '',
            'testMode' => false,
        );
    }

    public function getKey()
    {
        return $this->getParameter('key');
    }

    public function setKey($value)
    {
        return $this->setParameter('key', $value);
    }

    /**
     * @return Message\AuthorizeRequest
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayTrace\Message\AuthorizeRequest', $parameters);
    }
}
