<?php

namespace BnplPartners\Factoring004Diafan\Handler;

use BnplPartners\Factoring004\ChangeStatus\CancelOrder;
use BnplPartners\Factoring004\ChangeStatus\CancelStatus;
use BnplPartners\Factoring004\ChangeStatus\MerchantsOrders;
use BnplPartners\Factoring004\Exception\ErrorResponseException;
use BnplPartners\Factoring004\Response\ErrorResponse;
use BnplPartners\Factoring004\Transport\GuzzleTransport;
use BnplPartners\Factoring004\Transport\TransportInterface;
use BnplPartners\Factoring004Diafan\Helper\ApiCreationTrait;
use BnplPartners\Factoring004Diafan\Helper\Config;

class CancelHandler implements OrderStatusHandlerInterface
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

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return 'cancel';
    }

    /**
     * {@inheritDoc}
     */
    public function shouldProcess($orderStatusId)
    {
        return (string) $orderStatusId === Config::get('factoring004_status_cancel');
    }

    /**
     * {@inheritDoc}
     */
    public function handle(array $order, $amount = null)
    {
        $response = $this->createApi()
            ->changeStatus
            ->changeStatusJson([
                new MerchantsOrders($this->getMerchantId(), [
                    new CancelOrder((string) $order['id'], CancelStatus::CANCEL()),
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

        return false;
    }
}
