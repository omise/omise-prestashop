<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'omise/controllers/front/base.php';
}

class OmisePaymentReturnModuleFrontController extends OmiseBasePaymentModuleFrontController
{
    public function postProcess()
    {
        $class = OmisePaymentMethods::className(Tools::getValue('type'));
        $class::handleReturn($this);
    }

}
