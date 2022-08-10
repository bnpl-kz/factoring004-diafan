<?php

namespace BnplPartners\Factoring004Diafan\Handler;

use BnplPartners\Factoring004\ChangeStatus\ReturnOrder;
use BnplPartners\Factoring004\Otp\SendOtpReturn;

abstract class AbstractOrderStatusRefundHandler extends AbstractOrderStatusHandler
{
    /**
     * @return \BnplPartners\Factoring004\ChangeStatus\ReturnStatus
     */
    abstract protected function createReturnStatus();

    /**
     * {@inheritDoc}
     */
    protected function sendOtp($orderId, $totalAmount)
    {
        $this->createApi()
            ->otp
            ->sendOtpReturn(new SendOtpReturn($totalAmount, $this->getMerchantId(), $orderId));
    }

    /**
     * {@inheritDoc}
     */
    protected function createChangeStatusOrder($orderId, $totalAmount)
    {
        return new ReturnOrder($orderId, $this->createReturnStatus(), $totalAmount);
    }
}
