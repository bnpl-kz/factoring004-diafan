<?php

if (!defined('DIAFAN'))
{
    include dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/includes/404.php';
}

require_once 'vendor/autoload.php';

class Payment_factoring004_admin
{
    public $config;
    private $diafan;

    public function __construct(&$diafan)
    {
        $this->diafan = &$diafan;
        $this->diafan->_admin->js_view[] = 'modules/payment/backend/factoring004/assets/js/custom.js';
        $this->diafan->_admin->css_view[] = 'modules/payment/backend/factoring004/assets/css/style.css';
        $file_payment = file_get_contents('modules/payment/views/payment.view.list.php');

        if (!strripos($file_payment, 'require_once "modules/payment/backend/factoring004/payment.factoring004.custom.php";')) {
            file_put_contents('modules/payment/views/payment.view.list.php',PHP_EOL . 'require_once "modules/payment/backend/factoring004/payment.factoring004.custom.php";',FILE_APPEND);
        }

        $this->config = array(
            'name' => 'Рассрочка 0-0-4',
            'params' => array(
                'info' => array('name' => '<p><img src="/modules/payment/backend/factoring004/assets/img/factoring004.svg"></p><p>Рассрочка 0-0-4</p>', 'type' => 'info'),
                'factoring004_api_host' => 'API Host',
                'factoring004_pa_token' => 'OAuth Token bnpl-partners',
                'factoring004_as_token' => 'OAuth Token AccountingService',
                'factoring004_partner_name' => 'Partner Name',
                'factoring004_partner_code'=>'Partner Code',
                'factoring004_point_code'=>'Point Code',
                'factoring004_partner_email'=>'Partner Email',
                'factoring004_partner_website'=>'Partner Website',
                'factoring004_status_paid' => array(
                    'name' => 'Paid status',
                    'type'=>'function',
                ),
                'factoring004_status_unpaid' => array(
                    'name' => 'Unpaid status',
                    'type'=>'function',
                ),
            )
        );
    }

    public function edit_variable_factoring004_status_unpaid($value)
    {
        $html = '<div class="unit tr_payment" payment="factoring004"><div class="infofield">'.$this->diafan->_('Статус неоплаченных заказов').'</div><select name="factoring004_status_unpaid">';
        foreach ($this->getStatuses() as $status) {
            $statusName1 = isset($status['name1']) ? $status['name1'] : '';
            $statusName2 = isset($status['name2']) ? $status['name2'] : $statusName1;
            if ($status['id'] === $value) {
                $html .= '<option selected value='.$status['id'].'>'.$statusName1.'</option>';
            } else {
                $html .= '<option '.($statusName1 === 'Отменен' || $statusName2 === 'Canceled' ? 'selected' : '').' value='.$status['id'].'>'.$statusName1.'</option>';
            }
        }
        $html .= '</select></div>';
        echo $html;
    }

    public function edit_variable_factoring004_status_paid($value)
    {
        $html = '<div class="unit tr_payment" payment="factoring004"><div class="infofield">'.$this->diafan->_('Статус оплаченных заказов').'</div><select name="factoring004_status_paid">';
        foreach ($this->getStatuses() as $status) {
            $statusName1 = isset($status['name1']) ? $status['name1'] : '';
            $statusName2 = isset($status['name2']) ? $status['name2'] : $statusName1;
            if ($status['id'] === $value) {
                $html .= '<option selected value='.$status['id'].'>'.$statusName1.'</option>';
            } else {
                $html .= '<option '.($statusName1 === 'В обработке' || $statusName2 === 'In processing' ? 'selected' : '').' value='.$status['id'].'>'.$statusName1.'</option>';
            }
        }
        $html .= '</select></div>';
        echo $html;
    }

    public function save_variable_factoring004_status_paid()
    {
        return $_POST["factoring004_status_paid"];
    }

    public function save_variable_factoring004_status_unpaid()
    {
        return $_POST["factoring004_status_unpaid"];
    }

    public function getStatuses()
    {
        return DB::query_fetch_all("SELECT * FROM {shop_order_status}");
    }
}
