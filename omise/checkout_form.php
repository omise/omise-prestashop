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
     * @return string[]
     */
    public function getListOfExpirationYear()
    {
        $current_year = date('Y');
        $list_of_expiration_year = array();
        $maximum_expiration_year = date('Y') + 10;

        do {
            $list_of_expiration_year[] = $current_year++;
        } while ($current_year <= $maximum_expiration_year);

        return $list_of_expiration_year;
    }
}
