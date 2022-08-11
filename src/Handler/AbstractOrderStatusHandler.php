<?php

namespace BnplPartners\Factoring004Diafan\Handler;

use BnplPartners\Factoring004\ChangeStatus\MerchantsOrders;
use BnplPartners\Factoring004\Exception\ErrorResponseException;
use BnplPartners\Factoring004\Response\ErrorResponse;
use BnplPartners\Factoring004\Transport\GuzzleTransport;
use BnplPartners\Factoring004\Transport\TransportInterface;
use BnplPartners\Factoring004Diafan\Helper\ApiCreationTrait;
use BnplPartners\Factoring004Diafan\Helper\Config;

abstract class AbstractOrderStatusHandler implements OrderStatusHandlerInterface
{
    use ApiCreationTrait;

    /**
     * @var \BnplPartners\Factoring004\Transport\TransportInterface
     */
    protected $transport;

    public function __construct(TransportInterface $transport = null)
    {
        $this->transport = $transport ?: new GuzzleTransport();
    }

    final public function handle(array $order, $amount = null)
    {
        $amount = (int) ceil($amount === null ? $order['summ'] : $amount);

        if (in_array((string) $order['delivery_id'], $this->getConfirmableDeliveryMethods(), true)) {
            $this->sendOtp((string) $order['id'], $amount);
            return true;
        }

        $this->confirmWithoutOtp((string) $order['id'], $amount);
        return false;
    }

    /**
     * @param string $orderId
     * @param int $totalAmount
     *
     * @return void
     *
     * @throws \BnplPartners\Factoring004\Exception\PackageException
     */
    abstract protected function sendOtp($orderId, $totalAmount);

    /**
     * @param string $orderId
     * @param int $totalAmount
     *
     * @return \BnplPartners\Factoring004\ChangeStatus\AbstractMerchantOrder
     */
    abstract protected function createChangeStatusOrder($orderId, $totalAmount);

    /**
     * @param string $orderId
     * @param int $totalAmount
     *
     * @return void
     *
     * @throws \BnplPartners\Factoring004\Exception\PackageException
     */
    protected function confirmWithoutOtp($orderId, $totalAmount)
    {
        $response = $this->createApi()
            ->changeStatus
            ->changeStatusJson([
                new MerchantsOrders($this->getMerchantId(), [
                    $this->createChangeStatusOrder($orderId, $totalAmount),
                ]),
            ]);

        foreach ($response->getErrorResponses() as $errorResponse) {
            throw new ErrorResponseException(new ErrorResponse(
                $errorResponse->getCode(),
                $errorResponse->getMessage(),
                null,
                null,
                $errorResponse->getError()
            ));
        }
    }

    /**
     * @return string[]
     */
    protected function getConfirmableDeliveryMethods()
    {
        return Config::get('factoring004_delivery_items', []);
    }
}
