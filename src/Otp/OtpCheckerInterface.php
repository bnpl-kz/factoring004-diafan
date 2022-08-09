<?php

namespace BnplPartners\Factoring004Diafan\Otp;

interface OtpCheckerInterface
{
    /**
     * @param string $orderId
     * @param int $amount
     * @param string $otp
     *
     * @return void
     *
     * @throws \BnplPartners\Factoring004\Exception\PackageException
     */
    public function check($orderId, $amount, $otp);
}
