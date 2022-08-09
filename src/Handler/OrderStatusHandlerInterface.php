<?php

namespace BnplPartners\Factoring004Diafan\Handler;

interface OrderStatusHandlerInterface
{
    /**
     * @return string
     */
    public function getKey();

    /**
     * @param int|string $orderStatusId
     *
     * @return bool
     */
    public function shouldProcess($orderStatusId);

    /**
     * @param array<string, mixed> $order
     * @param int|float|null $amount Leave amount is null to get amount from order.
     *
     * @return bool if true OTP to be checked
     *
     * @throws \BnplPartners\Factoring004\Exception\PackageException
     */
    public function handle(array $order, $amount = null);
}
