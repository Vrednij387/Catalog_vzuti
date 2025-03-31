<?php


if (!defined('_PS_VERSION_')) {
    exit;
}

class ecm_orderreference extends Module
{
    protected $config_form = false;

    public $_html;

    public function __construct()
    {
        $this->name = 'ecm_orderreference';
        $this->tab = 'billing_invoicing';
        $this->version = '1.2.0';
        $this->author = 'elcommerce';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Order Reference');
        $this->description = $this->l('Changes the order reference of any order upon validation of the order.');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        return parent::install()
            && $this->registerHook('actionObjectOrderAddAfter')
            ;
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function hookActionObjectOrderAddAfter(array $params)
    {
        return $params['object']->reference = $this->changeReference($params['object']->id,$params['object']->reference);
    }
    public function changeReference($id_order,$reference)
    {
        $db = Db::getInstance();
        $db->update('order_payment', array('order_reference' => $id_order),  "`order_reference`='" . $reference."'");
        $db->update('orders', array('reference' => $id_order),  'id_order=' . (int)$id_order);
        return $id_order;
    }
}
