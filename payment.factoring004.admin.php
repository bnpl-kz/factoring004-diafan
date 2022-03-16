<?php

if (! defined('DIAFAN'))
{
    include dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/includes/404.php';
}



class Payment_factoring004_admin
{
    public $config;
    private $diafan;

    public function __construct(&$diafan)
    {
        $this->diafan = &$diafan;
        $this->diafan->_admin->js_view[] = 'modules/payment/backend/factoring004/assets/js/custom.js';
        $this->diafan->_admin->css_view[] = 'modules/payment/backend/factoring004/assets/css/style.css';
        $this->config = array(
            'name' => 'Рассрочка 0-0-4',
            'params' => array(
                'info' => array('name' => '<p><img src="/modules/payment/backend/factoring004/assets/img/factoring004.svg"></p><p>Рассрочка 0-0-4. Купи сейчас, плати потом</p>', 'type' => 'info'),
                'factoring004_consumer_key' => 'Consumer Key',
                'factoring004_consumer_secret' => 'Consumer Secret',
                'factoring004_api_host' => 'API Host',
                'factoring004_oauth_path' => array(
                    'name'=>'OAuth path',
                    'type'=>'function',
                ),
                'factoring004_oauth_token'=>'OAuth token',
                'factoring004_partner_name' => 'Partner Name',
                'factoring004_partner_code'=>'Partner Code',
                'factoring004_partner_email'=>'Partner Email',
                'factoring004_partner_website'=>'Partner Website',
                'factoring004_point_code'=>'Point Code',
                'factoring004_delivery_items' => array(
                    'name' => 'Delivery parameters',
                    'type'=>'function',
                ),
            )
        );
    }

    public function edit_variable_factoring004_delivery_items($values)
    {
        $html = '<div class="unit tr_payment" payment="factoring004" style="display:none"><div class="infofield">'.$this->diafan->_('Delivery parameters').'<i class="tooltip fa fa-question-circle" title="Зажав ctrl, можно выбрать несколько параметров."></i></div><select class="factoring004-delivery-items" size="2" multiple name="factoring004_delivery_items[]">';
        foreach ($this->getDeliveryItems() as $deliveryItem) {
            if (in_array($deliveryItem['id'], $values)) {
                $html .= '<option selected value='.$deliveryItem['id'].'>'.$deliveryItem['name1'].'</option>';
            } else {
                $html .= '<option value='.$deliveryItem['id'].'>'.$deliveryItem['name1'].'</option>';
            }

        }
        $html .= '</select></div>';
        echo $html;
    }

    public function save_variable_factoring004_delivery_items()
    {
        return $_POST["factoring004_delivery_items"];
    }

    public function edit_variable_factoring004_oauth_path()
    {
        echo '<div class="unit tr_payment" payment="factoring004" style="display:none"><div class="infofield">'.$this->diafan->_('OAuth path').'</div><input type="text" value="/" name="factoring004_oauth_path"></div>';
    }

    public function save_variable_factoring004_oauth_path()
    {
        if (!empty($_POST["factoring004_oauth_path"])) {
           return $_POST["factoring004_oauth_path"];
        }
    }

    public function getDeliveryItems()
    {
        return DB::query_fetch_all("SELECT * FROM diafan_shop_delivery");
    }


}
