<?php

namespace BnplPartners\Factoring004Diafan\Handler;

use BnplPartners\Factoring004\Api;
use BnplPartners\Factoring004\Auth\BearerTokenAuth;
use BnplPartners\Factoring004\ChangeStatus\MerchantsOrders;
use BnplPartners\Factoring004\Exception\ErrorResponseException;
use BnplPartners\Factoring004\Response\ErrorResponse;
use BnplPartners\Factoring004\Transport\GuzzleTransport;
use BnplPartners\Factoring004\Transport\TransportInterface;

abstract class AbstractOrderStatusHandler implements OrderStatusHandlerInterface
{
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
     * @return \BnplPartners\Factoring004\Api
     */
    protected function createApi()
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
    protected function getMerchantId()
    {
        return '';
    }

    /**
     * @return string[]
     */
    protected function getConfirmableDeliveryMethods()
    {
        $ids = '';

        return $ids ? array_map('trim', explode(',', $ids)) : [];
    }
}
