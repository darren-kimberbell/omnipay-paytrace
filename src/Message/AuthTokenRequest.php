<?php
/**
 * PayPal REST Token Request
 */

namespace Omnipay\PayTrace\Message;

/**
 * PayTrace REST Token Request
 *
 * With each API call, you’ll need to set request headers, including
 * an OAuth access token. Get an access token by using the OAuth
 * password token grant type with your username:password as your
 * Basic Auth credentials.
 *
 */
class AuthTokenRequest extends AbstractRequest
{
    public function getData()
    {
        return array('grant_type' => 'password');
    }

    protected function getEndpoint()
    {
        return parent::getEndpoint() . '/oauth/token';
    }

    public function sendData($data)
    {
        $body = $data ? http_build_query($data, '', '&') : null;
        $httpResponse = $this->httpClient->request(
            $this->getHttpMethod(),
            $this->getEndpoint(),
            array(
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
                'Cache-Control' => 'no-cache'
            ),
            $this->toJSON([
                "grant_type" => "password",
                "username" => $this->getUsername(),
                "password" => $this->getPassword()
            ])
        );
        // Empty response body should be parsed also as and empty array
        $body = (string) $httpResponse->getBody()->getContents();
        $jsonToArrayResponse = !empty($body) ? json_decode($body, true) : array();
        return $this->response = new Response($this, $jsonToArrayResponse, $httpResponse->getStatusCode());
    }
}
