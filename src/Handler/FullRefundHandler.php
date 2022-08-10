<?php

namespace BnplPartners\Factoring004Diafan\Handler;

use BnplPartners\Factoring004\ChangeStatus\ReturnStatus;

class FullRefundHandler extends AbstractOrderStatusRefundHandler
{
    const KEY = 'full_refund';

    /**
     * {@inheritDoc}
     */
    protected function createReturnStatus()
    {
        return ReturnStatus::RETURN();
    }

    /**
     * {@inheritDoc}
     */
    public function shouldProcess($orderStatusId)
    {
        return $orderStatusId === '3';
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return static::KEY;
    }
}
