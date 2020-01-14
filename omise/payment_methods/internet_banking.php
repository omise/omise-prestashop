<?php

class OmisePaymentMethod_InternetBanking extends OmisePaymentMethod
{

	const
		NAME = 'InternetBanking',
		PAYMENT_OPTION_NAME = 'omise-internet-banking-payment',
		DEFAULT_TITLE = 'Internet Banking',
		TEMPLATE = 'internet_banking_payment'
	;

	public static
		$usedSettings = array(
			'internet_banking_status'
		)
	;

}