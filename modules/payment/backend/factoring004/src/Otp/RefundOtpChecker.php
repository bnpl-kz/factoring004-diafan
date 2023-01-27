<?php

namespace BnplPartners\Factoring004Diafan\Otp;

use BnplPartners\Factoring004\Otp\CheckOtpReturn;
use BnplPartners\Factoring004\Transport\GuzzleTransport;
use BnplPartners\Factoring004\Transport\TransportInterface;
use BnplPartners\Factoring004Diafan\Helper\ApiCreationTrait;

class RefundOtpChecker implements OtpCheckerInterface
{
    use ApiCreationTrait;

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
}
