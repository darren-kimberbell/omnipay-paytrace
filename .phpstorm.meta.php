<?php

namespace PHPSTORM_META {

    /** @noinspection PhpIllegalArrayKeyTypeInspection */
    /** @noinspection PhpUnusedLocalVariableInspection */
    $STATIC_METHOD_TYPES = [
      \Omnipay\Omnipay::create('') => [
        'PayTrace' instanceof \Omnipay\PayTrace\PayTraceGateway,
      ],
      \Omnipay\Common\GatewayFactory::create('') => [
        'PayTrace' instanceof \Omnipay\PayTrace\PayTraceGateway,
      ],
    ];
}
