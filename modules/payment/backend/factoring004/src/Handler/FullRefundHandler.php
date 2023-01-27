<?php

namespace BnplPartners\Factoring004Diafan\Handler;

use BnplPartners\Factoring004\ChangeStatus\ReturnStatus;
use BnplPartners\Factoring004Diafan\Helper\Config;

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
        return $orderStatusId === Config::get('factoring004_status_return');
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return static::KEY;
    }
}
