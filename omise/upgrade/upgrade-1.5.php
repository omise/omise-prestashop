<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'omise/classes/omise_customer_model.php';
}

function upgrade_module_1_5($module)
{
    $omise_customer_model = new OmiseCustomerModel();

    return $omise_customer_model->createTable();
}
