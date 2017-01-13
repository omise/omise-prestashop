<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

class CheckoutForm
{
    /**
     * Return the list of expiration year.
     *
     * The first year in the list is current year.
     * The last year in the list is next 10 years.
     *
     * @return int[]
     */
    public function getListOfExpirationYear()
    {
        return range(date('Y'), date('Y') + 10);
    }
}
