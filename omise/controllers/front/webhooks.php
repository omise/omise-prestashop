<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'omise/classes/omise_webhooks.php';
    require_once _PS_MODULE_DIR_ . 'omise/events/omise_event_handler.php';
}

class OmiseWebhooksModuleFrontController extends ModuleFrontController
{
    /**
     * @var OmiseEventHandler
     */
    protected $omise_event_handler;

    /**
     * @var OmiseWebhooks
     */
    protected $omise_webhooks;

    public function __construct()
    {
        parent::__construct();

        $this->setOmiseEventHandler(new OmiseEventHandler());
        $this->setOmiseWebhooks(new OmiseWebhooks());
    }

    /**
     * @param mixed $request_body The array of JSON decoded from Omise event object.
     *
     * @return bool
     */
    protected function isRequestValid($request_body)
    {
        if (is_null($request_body)) {
            return false;
        }

        if (! array_key_exists('object', $request_body)) {
            return false;
        }

        if ($request_body['object'] !== 'event') {
            return false;
        }

        return true;
    }

    public function postProcess()
    {
        $this->setTemplate(IS_VERSION_17 ? 'module:omise/views/templates/front/webhooks.tpl' : 'webhooks.tpl');

        $request_body = $this->omise_webhooks->getRequestBody();

        if (! $this->isRequestValid($request_body)) {
            $this->omise_webhooks->sendRawHeaderAsBadRequest();
            return;
        }

        $this->omise_event_handler->handle($request_body);
    }

    /**
     * @param OmiseEventHandler $omise_event_handler The instance of class, OmiseEventHandler.
     */
    public function setOmiseEventHandler($omise_event_handler)
    {
        $this->omise_event_handler = $omise_event_handler;
    }

    /**
     * @param OmiseWebhooks $omise_webhooks The instance of class, OmiseWebhooks.
     */
    public function setOmiseWebhooks($omise_webhooks)
    {
        $this->omise_webhooks = $omise_webhooks;
    }
}
