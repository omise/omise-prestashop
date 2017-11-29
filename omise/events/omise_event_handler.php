<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'omise/events/omise_event_charge_complete.php';
}

class OmiseEventHandler
{
    /**
     * Call another class to perform function for each event. The class to be used for handling event has been
     * determined by using the attribute named, key, of Omise event object.
     *
     * @param mixed $event The array of JSON decoded from Omise event object.
     *
     * @return bool
     */
    public function handle($event)
    {
        switch ($event['key']) {
            case OmiseEventChargeComplete::KEY:
                $omise_event = new OmiseEventChargeComplete();
                break;

            default:
                return false;
        }

        return $omise_event->handle($event);
    }
}
