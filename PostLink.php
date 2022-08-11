<?php

if ( ! defined('DIAFAN'))
{
    $path = __FILE__;
    while(! file_exists($path.'/includes/404.php'))
    {
        $parent = dirname($path);
        if($parent == $path) exit;
        $path = $parent;
    }
    include $path.'/includes/404.php';
}

require_once 'vendor/autoload.php';

use BnplPartners\Factoring004\Signature\PostLinkSignatureValidator;
use BnplPartners\Factoring004Diafan\Helper\Config as Factoring004Config;

class PostLink
{
    const REQUEST_FIELDS = ['status', 'billNumber', 'preappId'];
    const STATUS_PREAPPROVED = 'preapproved';
    const STATUS_COMPLETED = 'completed';
    const STATUS_DECLINED = 'declined';
    const RESPONSE_COMPLETED = 'ok';

    public function __invoke(Payment_inc $payment_inc)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            header('Allow: POST');
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid request method']);
            return;
        }

        $request = json_decode(file_get_contents('php://input'), true);

        try {
            $this->validateRequest($request);
        } catch (Exception $e) {
            header('HTTP/1.1 400 Bad Request');
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
            return;
        }

        if ($request["status"] === self::STATUS_PREAPPROVED || $request["status"] === self::STATUS_DECLINED) {
            header('Content-Type: application/json');
            echo json_encode(['response' => $request["status"]]);
            return;
        }

        if ($request["status"] !== self::STATUS_COMPLETED) {
            header('HTTP/1.1 400 Bad Request');
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unsupported status: ' . $request["status"]]);
            return;
        }

        $pay = $payment_inc->check_pay($request["billNumber"], 'factoring004');

        DB::query(
            "INSERT INTO {factoring004_order_preapps}
                        (order_id, preapp_uid)
                        VALUES 
                        ('%d','%s')",
            $request["billNumber"], $request['preappId']
        );
        $payment_inc->success($pay, 'pay');

        header('Content-Type: application/json');
        echo json_encode(['response' => self::RESPONSE_COMPLETED]);
    }

    private function validateRequest($request)
    {
        foreach (self::REQUEST_FIELDS as $field) {
            if (empty($request[$field]) || !is_string($request[$field])) {
                throw new InvalidArgumentException('The field ' . $field . ' is required and must be a string');
            }
        }

        $this->validateSignature($request);
    }

    private function validateSignature($request)
    {
        PostLinkSignatureValidator::create(Factoring004Config::get('factoring004_partner_code'))->validateData($request);
    }
}