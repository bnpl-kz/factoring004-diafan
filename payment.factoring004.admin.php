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
        $this->createTable();
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
                'factoring004_delivery_items' => array(
                    'name' => 'Delivery parameters',
                    'type'=>'function',
                ),
                'factoring004_offer_file' => array(
                    'name' => 'Offer file',
                    'type'=>'function',
                ),
                'factoring004_status_paid' => array(
                    'name' => 'Paid status',
                    'type'=>'function',
                ),
                'factoring004_status_unpaid' => array(
                    'name' => 'Unpaid status',
                    'type'=>'function',
                ),
                'factoring004_status_delivery' => array(
                    'name' => 'Delivery status',
                    'type'=>'function',
                ),
                'factoring004_status_return' => array(
                    'name' => 'Return status',
                    'type'=>'function',
                ),
                'factoring004_status_cancel' => array(
                    'name' => 'Cancel status',
                    'type'=>'function',
                ),
            )
        );
    }

    public function edit_variable_factoring004_offer_file($value)
    {
        $html = '<div class="unit tr_payment" payment="factoring004"><div class="infofield">'.$this->diafan->_('Файл оферты').'</div>';
        if (!$value) {
            $html .= '<button id="factoring004-agreement-button" class="btn btn-primary" type="button">Загрузить</button>
                        <input style="display:none;" type="file" id="factoring004_offer_file"/>
                        <span style="display:block; font-style: italic">Загрузите файл оферты, если вам необходимо его отобразить клиенту</span>';
        } else {
            $html .= '<a target="_blank" href=/'.USERFILES.'/'.$value.' class="btn btn-success agreement-link">Просмотреть</a>
                      <button id="factoring004-agreement-file-remove" type="button" data-value='.$value.' class="btn btn-primary">Удалить</button>';
        }
        $html .= '<input type="hidden" name="factoring004_offer_file" id="factoring004_offer_file_name"/></div>';

        echo $html;
    }

    public function edit_variable_factoring004_status_cancel($value)
    {
        $html = '<div class="unit tr_payment" payment="factoring004"><div class="infofield">'.$this->diafan->_('Статус отмененных заказов').'</div><select name="factoring004_status_cancel">';
        foreach ($this->getStatuses() as $status) {
            if ($status['id'] === $value) {
                $html .= '<option selected value='.$status['id'].'>'.$status['name1'].'</option>';
            } else {
                $html .= '<option '.($status['name1'] === 'Отменен' || $status['name2'] === 'Canceled' ? 'selected' : '').' value='.$status['id'].'>'.$status['name1'].'</option>';
            }
        }
        $html .= '</select></div>';
        echo $html;
    }

    public function edit_variable_factoring004_status_return($value)
    {
        $html = '<div class="unit tr_payment" payment="factoring004"><div class="infofield">'.$this->diafan->_('Статус возвращенных заказов').'</div><select name="factoring004_status_return">';
        foreach ($this->getStatuses() as $status) {
            if ($status['id'] === $value) {
                $html .= '<option selected value='.$status['id'].'>'.$status['name1'].'</option>';
            } else {
                $html .= '<option '.($status['name1'] === 'Отменен' || $status['name2'] === 'Canceled' ? 'selected' : '').' value='.$status['id'].'>'.$status['name1'].'</option>';
            }
        }
        $html .= '</select></div>';
        echo $html;
    }

    public function edit_variable_factoring004_status_delivery($value)
    {
        $html = '<div class="unit tr_payment" payment="factoring004"><div class="infofield">'.$this->diafan->_('Статус доставленных заказов').'</div><select name="factoring004_status_delivery">';
        foreach ($this->getStatuses() as $status) {
            if ($status['id'] === $value) {
                $html .= '<option selected value='.$status['id'].'>'.$status['name1'].'</option>';
            } else {
                $html .= '<option '.($status['name1'] === 'Выполнен' || $status['name2'] === 'Completed' ? 'selected' : '').' value='.$status['id'].'>'.$status['name1'].'</option>';
            }
        }
        $html .= '</select></div>';
        echo $html;
    }

    public function edit_variable_factoring004_status_unpaid($value)
    {
        $html = '<div class="unit tr_payment" payment="factoring004"><div class="infofield">'.$this->diafan->_('Статус неоплаченных заказов').'</div><select name="factoring004_status_unpaid">';
        foreach ($this->getStatuses() as $status) {
            if ($status['id'] === $value) {
                $html .= '<option selected value='.$status['id'].'>'.$status['name1'].'</option>';
            } else {
                $html .= '<option '.($status['name1'] === 'Отменен' || $status['name2'] === 'Canceled' ? 'selected' : '').' value='.$status['id'].'>'.$status['name1'].'</option>';
            }
        }
        $html .= '</select></div>';
        echo $html;
    }

    public function edit_variable_factoring004_status_paid($value)
    {
        $html = '<div class="unit tr_payment" payment="factoring004"><div class="infofield">'.$this->diafan->_('Статус оплаченных заказов').'</div><select name="factoring004_status_paid">';
        foreach ($this->getStatuses() as $status) {
            if ($status['id'] === $value) {
                $html .= '<option selected value='.$status['id'].'>'.$status['name1'].'</option>';
            } else {
                $html .= '<option '.($status['name1'] === 'В обработке' || $status['name2'] === 'In processing' ? 'selected' : '').' value='.$status['id'].'>'.$status['name1'].'</option>';
            }
        }
        $html .= '</select></div>';
        echo $html;
    }

    public function edit_variable_factoring004_delivery_items($values)
    {
        if (!$values) {
            $values = [];
        }
        $html = '<div class="unit tr_payment" payment="factoring004" style="display:grid"><div class="infofield">'.$this->diafan->_('Способы доставки').'</div>';
        foreach ($this->getDeliveryItems() as $deliveryItem) {
            if (in_array($deliveryItem['id'], $values)) {
                $html .= '<input id='.$deliveryItem['id'].' name="factoring004_delivery_items[]" checked type="checkbox" value='.$deliveryItem['id'].'><label for='.$deliveryItem['id'].'>'.$deliveryItem['name1'].'</label>';
            } else {
                $html .= '<input id='.$deliveryItem['id'].' name="factoring004_delivery_items[]" type="checkbox" value='.$deliveryItem['id'].'><label for='.$deliveryItem['id'].'>'.$deliveryItem['name1'].'</label>';
            }
        }
        $html .= '</div>';
        echo $html;
    }

    public function save_variable_factoring004_offer_file()
    {
        return $_POST["factoring004_offer_file"];
    }

    public function save_variable_factoring004_delivery_items()
    {
        return $_POST["factoring004_delivery_items"];
    }

    public function save_variable_factoring004_status_paid()
    {
        return $_POST["factoring004_status_paid"];
    }

    public function save_variable_factoring004_status_unpaid()
    {
        return $_POST["factoring004_status_unpaid"];
    }

    public function save_variable_factoring004_status_delivery()
    {
        return $_POST["factoring004_status_delivery"];
    }

    public function save_variable_factoring004_status_return()
    {
        return $_POST["factoring004_status_return"];
    }

    public function save_variable_factoring004_status_cancel()
    {
        return $_POST["factoring004_status_cancel"];
    }

    public function getDeliveryItems()
    {
        return DB::query_fetch_all("SELECT * FROM {shop_delivery}");
    }

    public function getStatuses()
    {
        return DB::query_fetch_all("SELECT * FROM {shop_order_status}");
    }

    private function createTable()
    {
        DB::query(
            "CREATE TABLE IF NOT EXISTS {factoring004_order_preapps} (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `order_id` int(11) NOT NULL,
              `preapp_uid` varchar(255) NOT NULL,
              `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY(`id`),
                UNIQUE(`order_id`,`preapp_uid`)
            ) ENGINE=InnoDB;"
        );
    }
}
