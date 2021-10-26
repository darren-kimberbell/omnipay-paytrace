<?php

namespace Omnipay\PayTrace;

use Omnipay\Common\AbstractGateway;

/**
 * PayTrace Gateway
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'PayTrace';
    }

    public function getDefaultParameters()
    {
        return array(
            'token' => '',
            'username' => '',
            'password' => '',
            'integratorId' => '',
            'testMode' => false,
        );
    }

    public function getParameters()
    {
        return parent::getParameters();
    }

    /**
     * Get OAuth access token.
     *
     * @param bool $createIfNeeded [optional] - If there is not an active token present, should we create one?
     * @return string
     */
    public function getToken($createIfNeeded = true)
    {
        if ($createIfNeeded && !$this->hasToken()) {
            $response = $this->createToken()->send();
            if ($response->isSuccessful()) {
                $data = $response->getData();
                if (isset($data['access_token'])) {
                    $this->setToken($data['access_token']);
                    $this->setTokenExpires(time() + $data['expires_in']);
                }
            }
        }

        return $this->getParameter('token');
    }

    /**
     * Create OAuth access token request.
     *
     * @return \Omnipay\PayTrace\Message\AuthTokenRequest
     */
    public function createToken()
    {
        return $this->createRequest('\Omnipay\PayTrace\Message\AuthTokenRequest', array());
    }

    /**
     * Set OAuth access token.
     *
     * @param string $value
     * @return Gateway provides a fluent interface
     */
    public function setToken($value)
    {
        return $this->setParameter('token', $value);
    }

    /**
     * Get OAuth access token expiry time.
     *
     * @return integer
     */
    public function getTokenExpires()
    {
        return $this->getParameter('tokenExpires');
    }

    /**
     * Set OAuth access token expiry time.
     *
     * @param integer $value
     * @return RestGateway provides a fluent interface
     */
    public function setTokenExpires($value)
    {
        return $this->setParameter('tokenExpires', $value);
    }

    /**
     * Is there a bearer token and is it still valid?
     *
     * @return bool
     */
    public function hasToken()
    {
        $token = $this->getParameter('token');

        $expires = $this->getTokenExpires();
        if (!empty($expires) && !is_numeric($expires)) {
            $expires = strtotime($expires);
        }

        return !empty($token) && time() < $expires;
    }

    /**
     * Get OAuth username for the access token.
     *
     * Get an access token by using the OAuth password token grant
     * type with your username:password as your Basic Auth credentials.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->getParameter('username');
    }

    /**
     * Set OAuth username for the access token.
     *
     * Get an access token by using the OAuth password token grant
     * type with your username:password as your Basic Auth credentials.
     *
     * @param string $value
     * @return Gateway provides a fluent interface
     */
    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    /**
     * Get OAuth password for the access token.
     *
     * Get an access token by using the OAuth password token grant
     * type with your username:password as your Basic Auth credentials.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }

    /**
     * Set OAuth password for the access token.
     *
     * Get an access token by using the OAuth password token grant
     * type with your username:password as your Basic Auth credentials.
     *
     * @param string $value
     * @return Gateway provides a fluent interface
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    /**
     * Get PayTrace Integrator ID
     *
     * @return string
     */
    public function getIntegratorId()
    {
        return $this->getParameter('integratorId');
    }

    /**
     * Set PayTrace Integrator ID
     *
     * @param string $value
     * @return Gateway provides a fluent interface
     */
    public function setIntegratorId($value)
    {
        return $this->setParameter('integratorId', $value);
    }

    /**
     * Create Request
     *
     * This overrides the parent createRequest function ensuring that the OAuth
     * access token is passed along with the request data -- unless the
     * request is a AuthTokenRequest in which case no token is needed.  If no
     * token is available then a new one is created (e.g. if there has been no
     * token request or the current token has expired).
     *
     * @param string $class
     * @param array $parameters
     * @return \Omnipay\PayTrace\Message\AbstractRequest
     */
    public function createRequest($class, array $parameters = array())
    {
        if (!$this->hasToken() && $class != '\Omnipay\PayTrace\Message\AuthTokenRequest') {
            // This will set the internal token parameter which the parent
            // createRequest will find when it calls getParameters().
            $this->getToken(true);
        }

        return parent::createRequest($class, $parameters);
    }

    /**
     * Authorize a transaction
     *
     * @param array $parameters
     * @return Message\AuthorizeRequest
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayTrace\Message\AuthorizeRequest', $parameters);
    }

    /**
     * Store a credit card in the vault
     *
     * You can currently use the vault API to store credit card details
     * with PayTrace instead of storing them on your own server. After storing
     * a credit card, you can then pass the credit card id instead of the
     * related credit card details to complete a payment.
     *
     * @param array $parameters
     * @return \Omnipay\PayTrace\Message\CreateCardRequest
     */
    public function createCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayTrace\Message\CreateCardRequest', $parameters);
    }

    /**
     * Update a credit card in the vault
     *
     * @param array $parameters
     * @return \Omnipay\PayTrace\Message\UpdateCardRequest
     */
    public function updateCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayTrace\Message\UpdateCardRequest', $parameters);
    }

    /**
     * Delete a credit card from the vault
     *
     * @param array $parameters
     * @return \Omnipay\PayTrace\Message\DeleteCardRequest
     */
    public function deleteCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayTrace\Message\DeleteCardRequest', $parameters);
    }

    /**
     * Capture a pre-authorized transaction
     *
     * @param array $parameters
     * @return Message\CaptureRequest
     */
    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayTrace\Message\CaptureRequest', $parameters);
    }

    /**
     * Complete an authorized request - same as capture?
     *
     * @param array $parameters
     * @return Message\CaptureRequest
     */
    public function completeAuthorize(array $parameters = array())
    {
        return $this->capture($parameters);
    }

    /**
     * Process a sale transaction (auth/capture)
     *
     * @param array $parameters
     * @return Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayTrace\Message\PurchaseRequest', $parameters);
    }

    /**
     * Not needed?
     *
     * I really don't know what this function is for, as the
     * purchase transaction includes both auth and capture.
     *
     * I have included it only because it is required by the
     * GatewayInterface PHP interface
     *
     */
    //public function completePurchase(array $parameters = array())
    //{
    //    return true;
    //}

    /**
     * Process a refund transaction
     *
     * @param array $parameters
     * @return Message\RefundRequest
     */
    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayTrace\Message\RefundRequest', $parameters);
    }

    /**
     * Void an unsettled transaction
     *
     * @param array $parameters
     * @return Message\VoidRequest
     */
    public function void(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayTrace\Message\VoidRequest', $parameters);
    }

    /**
     * Fetch a Sale Transaction
     *
     * @param array $parameters
     * @return \Omnipay\PayTrace\Message\FetchTransactionRequest
     */
    public function fetchTransaction(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayTrace\Message\FetchTransactionRequest', $parameters);
    }

}
