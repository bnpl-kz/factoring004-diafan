<?php

namespace BnplPartners\Factoring004Diafan\Otp;

use BnplPartners\Factoring004\Api;
use BnplPartners\Factoring004\Auth\BearerTokenAuth;
use BnplPartners\Factoring004\Otp\CheckOtpReturn;
use BnplPartners\Factoring004\Transport\GuzzleTransport;
use BnplPartners\Factoring004\Transport\TransportInterface;

class RefundOtpChecker implements OtpCheckerInterface
{
    /**
     * @var \BnplPartners\Factoring004\Transport\TransportInterface
     */
    private $transport;

    public function __construct(TransportInterface $transport = null)
    {
        $this->transport = $transport ?: new GuzzleTransport();
    }

    /**
     * {@inheritDoc}
     */
    public function check($orderId, $amount, $otp)
    {
        $this->createApi()->otp->checkOtpReturn(new CheckOtpReturn($amount, $this->getMerchantId(), $orderId, $otp));
    }

    /**
     * @return \BnplPartners\Factoring004\Api
     */
    private function createApi()
    {
        return Api::create(
            'http://localhost',
            new BearerTokenAuth(''),
            $this->transport
        );
    }

    /**
     * @return string
     */
    private function getMerchantId()
    {
        return '';
    }
}
