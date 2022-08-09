<?php

namespace BnplPartners\Factoring004Diafan\Handler;

use BnplPartners\Factoring004\ChangeStatus\DeliveryOrder;
use BnplPartners\Factoring004\ChangeStatus\DeliveryStatus;
use BnplPartners\Factoring004\Otp\SendOtp;

class DeliveryHandler extends AbstractOrderStatusHandler
{
    const KEY = 'delivery';

    /**
     * {@inheritDoc}
     */
    protected function sendOtp($orderId, $totalAmount)
    {
        $this->createApi()
            ->otp
            ->sendOtp(new SendOtp($this->getMerchantId(), $orderId, $totalAmount));
    }

    /**
     * {@inheritDoc}
     */
    protected function createChangeStatusOrder($orderId, $totalAmount)
    {
        return new DeliveryOrder($orderId, DeliveryStatus::DELIVERED(), $totalAmount);
    }

    /**
     * {@inheritDoc}
     */
    public function shouldProcess($orderStatusId)
    {
        return $orderStatusId === '4';
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return static::KEY;
    }
}
