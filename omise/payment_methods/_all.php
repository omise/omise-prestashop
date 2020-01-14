<?php

class OmisePaymentMethods
{
	static $list = array(
		'Card',
		'InternetBanking',
		'Alipay'
	);
}

class OmisePaymentMethod
{

	public static
		$payModule,
		$smarty
	;

	public static function display()
	{
		return self::$payModule->versionSpecificDisplay(static::TEMPLATE . '.tpl');
	}

}


foreach (OmisePaymentMethods::$list as $method) require_once _PS_MODULE_DIR_ . 'omise/payment_methods/' . strToLower(preg_replace('%([a-z])([A-Z])%', '\1_\2', $method)) . '.php';

