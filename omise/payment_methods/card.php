<?php

class OmisePaymentMethod_Card extends OmisePaymentMethod
{

	const
		PAYMENT_OPTION_NAME = 'omise-card-payment',
		DEFAULT_TITLE = 'Pay by Credit / Debit Card',
		TEMPLATE = "card_payment"
	;

	public static function display()
	{
		$pm = OmisePaymentMethod::$payModule;
    OmisePaymentMethod::$smarty->assign(array(
      'action' => $pm->getAction(),
      'list_of_expiration_year' => $pm->checkout_form->getListOfExpirationYear(),
      'omise_public_key' => $pm->setting->getPublicKey(),
      'omise_title' => $pm->setting->getTitle()
    ));

    return $pm->versionSpecificDisplay(self::TEMPLATE . '.tpl');
	}	

}
