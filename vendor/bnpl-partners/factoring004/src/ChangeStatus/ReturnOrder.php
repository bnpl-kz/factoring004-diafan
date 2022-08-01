<?php

namespace BnplPartners\Factoring004\ChangeStatus;

/**
 * @extends AbstractMerchantOrder<ReturnStatus>
 */
class ReturnOrder extends AbstractMerchantOrder
{
    /**
     * @var int
     */
    private $amount;

    /**
     * @param string $orderId
     * @param int $amount
     */
    public function __construct($orderId, ReturnStatus $status, $amount)
    {
        $orderId = (string) $orderId;
        $amount = (int) $amount;
        parent::__construct($orderId, $status);

        $this->amount = $amount;
    }

    /**
     * @param array<string, mixed> $order
     * @psalm-param array{orderId: string, status: string, amount: int} $order
     * @return \BnplPartners\Factoring004\ChangeStatus\ReturnOrder
     */
    public static function createFromArray($order)
    {
        return new self($order['orderId'], new ReturnStatus($order['status']), $order['amount']);
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @psalm-return array{orderId: string, status: string, amount: int}
     * @return mixed[]
     */
    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'amount' => $this->getAmount(),
        ]);
    }
}
