<?php

if (! defined('DIAFAN'))
{
    include dirname(dirname(dirname(dirname(dirname(__FILE__))))).'../includes/404.php';
}

require_once 'vendor/autoload.php';

use BnplPartners\Factoring004\Api;
use BnplPartners\Factoring004\Auth\BearerTokenAuth;
use BnplPartners\Factoring004\PreApp\PreAppMessage;

class Factoring004
{

    private $api;

    public function __construct($base_url, $token)
    {
        $this->api = Api::create($base_url, new BearerTokenAuth($token));
    }

    public function createPreapp(array $data)
    {
        $message = PreAppMessage::createFromArray([
            'partnerData' => [
                'partnerName' => $data['factoring004_partner_name'],
                'partnerCode' => $data['factoring004_partner_code'],
                'pointCode' => $data['factoring004_point_code'],
                'partnerEmail' => $data['factoring004_partner_email'],
                'partnerWebsite' => $data['factoring004_partner_website'],
            ],
            'phoneNumber'=> empty($data['details']['phone'])
                ? null
                : preg_replace('/^8|\+7/', '7', $data['details']['phone']),
            'billNumber' => $data["element_id"],
            'billAmount' => (int) round($data['summ']),
            'itemsQuantity' => $this->getItemsQuantityCount($data['details']['goods']),
            'successRedirect' => 'http'.(IS_HTTPS ? "s" : '').'://'.$_SERVER['HTTP_HOST'],
            'failRedirect' => 'http'.(IS_HTTPS ? "s" : '').'://'.$_SERVER['HTTP_HOST'],
            'postLink' => 'http'.(IS_HTTPS ? "s" : '').'://'.$_SERVER['HTTP_HOST'].'/payment/get/factoring004/post-link',
            'items' => $this->getItems($data['details']['goods']),
            'deliveryPoint'=> [
                'city'=> isset($data['details']['city']) ? $data['details']['city'] : '',
                'street'=> isset($data['details']['street']) ? $data['details']['street'] : '',
                'house'=> isset($data['details']['building']) ? $data['details']['building'] : '',
                'flat'=> isset($data['details']['flat']) ? $data['details']['flat'] : ''
            ]
        ]);
        return $this->api->preApps->preApp($message)->getRedirectLink();
    }

    private function getItemsQuantityCount(array $data)
    {
        $count = 0;
        foreach ($data as $item) {
            $count += $item['count'];
        }
        return $count;
    }

    private function getItems(array $data)
    {
        $items = [];
        foreach ($data as $item) {
            $items[]=[
                'itemId' => hash('sha256',str_replace(' ','',$item['name'])),
                'itemName' => $item['name'],
                'itemCategory' => hash('sha256',str_replace(' ','',$item['name'])),
                'itemQuantity' => (int) $item['count'],
                'itemPrice' => (int) round($item['price']),
                'itemSum' => (int) round($item['price']),
            ];
        }
        return $items;
    }

}
