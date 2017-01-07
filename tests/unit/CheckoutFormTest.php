<?php
class CheckoutFormTest extends PHPUnit_Framework_TestCase
{
    private $list_of_expiration_year;

    public function setup()
    {
        $checkout_form = new CheckoutForm();
        $this->list_of_expiration_year = $checkout_form->getListOfExpirationYear();
    }

    public function testGetListOfExpirationYear_getListOfExpirationYear_totalNumberOfExpirationYearMustBe11()
    {
        $this->assertEquals(11, count($this->list_of_expiration_year));
    }

    public function testGetListOfExpirationYear_getListOfExpirationYear_theBeginningOfExpirationYearMustBeCurrentYear()
    {
        $current_year = date('Y');

        $beginning_of_expiration_year = $this->list_of_expiration_year[0];

        $this->assertEquals($current_year, $beginning_of_expiration_year);
    }

    public function testGetListOfExpirationYear_getListOfExpirationYear_theEndingOfExpirationYearMustBeNextTenYears()
    {
        $next_ten_year = date('Y') + 10;

        $end_of_expiration_year = end($this->list_of_expiration_year);

        $this->assertEquals($next_ten_year, $end_of_expiration_year);
    }
}
