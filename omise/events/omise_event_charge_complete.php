<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'omise/classes/omise_charge_class.php';
    require_once _PS_MODULE_DIR_ . 'omise/events/omise_base_event.php';
}

class OmiseEventChargeComplete extends OmiseBaseEvent
{
    const KEY = 'charge.complete';

    /**
     * @param mixed $event The array of JSON decoded from Omise event object.
     *
     * @return bool
     */
    public function handle($event)
    {
        $charge = $event['data'];

        $id_order = $this->omise_transaction_model->getIdOrder($charge['id']);
        $order = new Order($id_order);

        if (! Validate::isLoadedObject($order)) {
            return false;
        }

        $message = 'Omise Webhooks: an event charge.complete, ' . $event['id'] . ', has been caught.';

        switch ($charge['status']) {
            case OmiseChargeClass::STATUS_FAILED:
                $this->payment_order->updateStateToBeCanceled($order);

                $message .= ' The status of order, ' . $id_order . ', has been updated to be Canceled.';
                $this->omise_logger->add($message);
                break;

            case OmiseChargeClass::STATUS_SUCCESSFUL:
                $this->payment_order->updateStateToBeSuccess($order);

                $message .= ' The status of order, ' . $id_order . ', has been updated to be Payment accepted.';
                $this->omise_logger->add($message);
                break;
        }

        return true;
    }
}
