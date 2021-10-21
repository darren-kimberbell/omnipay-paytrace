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
            'intigratorId' => '',
            'testMode' => false,
        );
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
        return $this->getParameter('intigratorId');
    }

    /**
     * Set PayTrace Integrator ID
     *
     * @param string $value
     * @return Gateway provides a fluent interface
     */
    public function setIntegratorId($value)
    {
        return $this->setParameter('intigratorId', $value);
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
     * @return Message\AuthorizeRequest
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayTrace\Message\AuthorizeRequest', $parameters);
    }
}
