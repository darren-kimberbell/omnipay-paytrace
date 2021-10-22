<?php

namespace Omnipay\PayTrace\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Response
 */
class Response extends AbstractResponse
{
    protected $statusCode;

    public function __construct(RequestInterface $request, $data, $statusCode = 200)
    {
        //parent::__construct($request, $data);
        $this->statusCode = $statusCode;
        $this->request = $request;
        $this->data = $data;
    }

    public function isSuccessful()
    {
        return empty($this->data['error']) &&
            ($this->getCode() < 400) &&
            (
             (!empty($this->data['success']) && (bool) $this->data['success']) ||
             (!empty($this->data['access_token']))
            );
    }

    public function getMessage()
    {
        if (isset($this->data['error_description'])) {
            return $this->data['error_description'];
        }

        if (isset($this->data['status_message'])) {
            return $this->data['status_message'];
        }

        return null;
    }

    public function getCode()
    {
        return $this->statusCode;
    }

    public function getTransactionReference()
    {
        if (isset($this->data['transaction_id'])) {
            return $this->data['transaction_id'];
        }
    }

    public function getCardReference()
    {
        if (isset($this->data['customer_id'])) {
            return $this->data['customer_id'];
        }
    }

}
