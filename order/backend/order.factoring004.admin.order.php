<?php

use BnplPartners\Factoring004\Exception\ErrorResponseException;
use BnplPartners\Factoring004\Exception\PackageException;
use BnplPartners\Factoring004Diafan\Handler\DeliveryHandler;
use BnplPartners\Factoring004Diafan\Helper\SessionTrait;
use BnplPartners\Factoring004Diafan\Otp\DeliveryOtpChecker;

if (!defined('DIAFAN')) {
    $path = __FILE__;

    while (!file_exists($path . '/includes/404.php')) {
        $parent = dirname($path);
        if ($parent == $path) {
            exit;
        }
        $path = $parent;
    }

    include $path . '/includes/404.php';
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/modules/payment/backend/factoring004/vendor/autoload.php';

class Order_factoring004_admin_order extends Diafan
{
    use SessionTrait;

    /**
     * @return void
     */
    public function edit()
    {
        $error = $this->pullSession('error_' . $this->diafan->id);

        if ($error) {
            echo '<div class="errors error">' . $error . '</div>';
        }

        $prevData = $this->pullSession('order_data_' . $this->diafan->id, []);
        $fieldsSet = $this->buildFieldValues($prevData);

        if ($this->pullSession('send_otp_' . $this->diafan->id)) {
            echo include_once __DIR__ . '/views/modal_check_otp.php';
        }

        if ($fieldsSet) {
            echo sprintf(
                "<script>document.addEventListener('DOMContentLoaded', () => {\n%s});</script>", implode(";\n", $fieldsSet)
            );
        }
    }

    /**
     * @return void
     */
    public function save()
    {
        $this->putSession('order_data_' . $this->diafan->id, $_POST);
        $order = $this->findOrderById($this->diafan->id);

        if ($order['payment_history_id'] === null) {
            return;
        }

        try {
            if (isset($_POST['otp'])) {
                $this->checkOtp($order, $_POST['otp'], $_POST['status_id']);
                return;
            }

            $redirectLink = $this->changeOrderStatus($order, $_POST['status_id']);

            if ($redirectLink) {
                $this->diafan->redirect($redirectLink);
                exit;
            }
        } catch (ErrorResponseException $e) {
            $response = $e->getErrorResponse();

            $this->putSession('error_' . $this->diafan->id, $response->getError() . ': ' . $response->getMessage());
            $this->diafan->redirect($this->getBackUrl());
            exit;
        } catch (PackageException $e) {
            $this->putSession(
                'error_' . $this->diafan->id,
                (MOD_DEVELOPER || MOD_DEVELOPER_ADMIN) ? $e->getMessage() : 'An error occurred'
            );

            $this->diafan->redirect($this->getBackUrl());
            exit;
        }
    }

    /**
     * @param int|string $orderId
     *
     * @return array<string, mixed>
     */
    private function findOrderById($orderId)
    {
        return DB::query_fetch_array(
            "SELECT o.*, h.id as payment_history_id FROM {shop_order} o
             LEFT JOIN {payment_history} h ON h.element_id = o.id
                 AND h.payment_id = (SELECT id FROM {payment} WHERE payment = 'factoring004')
             WHERE o.id = %d LIMIT 1",
            $orderId
        );
    }

    /**
     * @param array<string, mixed> $order
     * @param string $otp
     * @param string $statusId
     *
     * @return void
     *
     * @throws \BnplPartners\Factoring004\Exception\PackageException
     */
    private function checkOtp(array $order, $otp, $statusId)
    {
        $this->resolveOtpChecker($statusId)->check($order['id'], (int) ceil($order['summ']), $otp);
    }

    /**
     * @param array<string, mixed> $order
     * @param string $statusId
     *
     * @return string|null
     *
     * @throws \BnplPartners\Factoring004\Exception\PackageException
     */
    private function changeOrderStatus(array $order, $statusId)
    {
        try {
            $handler = $this->resolveOrderStatusHandler($order['status_id'], $statusId);
        } catch (InvalidArgumentException $e) {
            return null;
        }

        $shouldConfirmOtp = $handler->handle($order);

        if ($shouldConfirmOtp) {
            $this->putSession('send_otp_' . $this->diafan->id, true);
            return $this->getBackUrl();
        }

        return null;
    }

    /**
     * @param string $currentOrderStatus
     * @param string $newOrderStatus
     *
     * @return \BnplPartners\Factoring004Diafan\Handler\OrderStatusHandlerInterface
     *
     * @throws \InvalidArgumentException
     */
    private function resolveOrderStatusHandler($currentOrderStatus, $newOrderStatus)
    {
        if ($newOrderStatus === '4') {
            return new DeliveryHandler();
        }

        throw new InvalidArgumentException('Order status handler not found');
    }

    /**
     * @param string $statusId
     *
     * @return \BnplPartners\Factoring004Diafan\Otp\OtpCheckerInterface
     */
    private function resolveOtpChecker($statusId)
    {
        if ($statusId === '4') {
            return new DeliveryOtpChecker();
        }

        throw new InvalidArgumentException('OTP checker not found');
    }

    /**
     * @return string
     */
    private function getBackUrl()
    {
        return 'http://localhost:8080/admin/order/edit' . $this->diafan->id . '/';
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return string[]
     */
    private function buildFieldValues(array $data)
    {
        unset($data['check_hash_user']);

        $items = [];

        foreach ($data as $key => $value) {
            if ($key === 'check_hash_user') {
                continue;
            }

            if (is_array($value)) {
                foreach ($value as $id => $val) {
                    $items[] = sprintf("$('[name=\"%s[%s]\"]').val('%s')", $key, $id, $val);
                }
            } else {
                $items[] = "$('[name=\"$key\"]').val('$value')";
            }
        }

        return $items;
    }
}
