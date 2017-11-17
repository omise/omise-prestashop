<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

class OmiseWebhooksModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        $this->setTemplate('module:omise/views/templates/front/webhooks.tpl');
    }
}
