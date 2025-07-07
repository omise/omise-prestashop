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
        $message = 'Omise Webhooks: an event charge.complete, ' . $event['id'] . ', has been caught.';

        try {
            $order = $this->getOrder($charge['id']);
            $omise_charge = $this->getOmiseCharge($charge['id'], $message);
        } catch (Exception $e) {
            return false;
        }

        switch ($omise_charge->getStatus()) {
            case OmiseChargeClass::STATUS_FAILED:
                $this->payment_order->updateStateToBeCanceled($order);

                $message .= ' The status of order, ' . $order->id . ', has been updated to be Canceled.';
                $this->omise_logger->add($message);
                break;

            case OmiseChargeClass::STATUS_SUCCESSFUL:
                $this->payment_order->updateStateToBeSuccess($order);

                $message .= ' The status of order, ' . $order->id . ', has been updated to be Payment accepted.';
                $this->omise_logger->add($message);
                break;
        }

        return true;
    }

    private function getOrder($id_charge)
    {
        $id_order = $this->omise_transaction_model->getIdOrder($id_charge);
        $order = new Order($id_order);

        if (! Validate::isLoadedObject($order)) {
            throw new Exception('Order not found for charge ID: ' . $id_charge);
        }

        return $order;
    }

    private function getOmiseCharge($id_charge, $message)
    {
        try {
            $omise_charge = new OmiseChargeClass();
            return $omise_charge->retrieve($id_charge);
        } catch (Exception $e) {
            $message .= ' The charge, ' . $id_charge . ', cannot be retrieved (' . $e->getMessage() . ').';
            $this->omise_logger->add($message, OmiseLogger::ERROR);

            throw $e;
        }
    }
}
