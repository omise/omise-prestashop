<?php
if (! class_exists('OmisePluginHelperCharge')) {
    class OmisePluginHelperCharge
    {

        private static
            $currencyDecimals = [
                "BIF" => 0,
                "CLP" => 0,
                "DJF" => 0,
                "GNF" => 0,
                "ISK" => 0,
                "JPY" => 0,
                "KMF" => 0,
                "KRW" => 0,
                "PYG" => 0,
                "RWF" => 0,
                "UGX" => 0,
                "UYI" => 0,
                "VND" => 0,
                "VUV" => 0,
                "XAF" => 0,
                "XOF" => 0,
                "XPF" => 0,
                "BHD" => 3,
                "IQD" => 3,
                "JOD" => 3,
                "KWD" => 3,
                "LYD" => 3,
                "OMR" => 3,
                "TND" => 3,
            ],
            $defaultCurrencyDecimals = 2,
            $supportedCurrencies = [
                'AUD',
                'CAD',
                'CHF',
                'CNY',
                'DKK',
                'EUR',
                'GBP',
                'HKD',
                'JPY',
                'MYR',
                'THB',
                'SGD',
                'USD'
            ]
        ;

        /**
         * Return number of subunits the passed currency has
         *
         * @param string $currency
         * @return integer
         */
        public static function subUnitsFor($curr) {
            $c = strtoupper($curr);
            $decimals = isset(self::$currencyDecimals[$c]) ? self::$currencyDecimals[$c] : self::$defaultCurrencyDecimals;
            return pow(10, $decimals);
        }        

        /**
         *
         * @param string $currency
         * @param numeric $amount
         * @return string
         */
        public static function amount($currency, $amount)
        {
            return $amount * self::subUnitsFor($currency);
        }

        /**
         *
         * @param \omise-php\OmiseCharge $charge
         * @return string
         */
        public static function getErrorMessage($charge)
        {
            if ($charge['failure_code'] !== '') {
                return '(' . $charge['failure_code'] . ') ' . $charge['failure_message'];
            }

            return '';
        }

        /**
         *
         * @param \omise-php\OmiseCharge $charge
         * @return boolean
         */
        public static function isAuthorized($charge)
        {
            if (! self::isChargeObject($charge)) {
                return false;
            }

            if ($charge['authorized'] === true) {
                return true;
            }

            return false;
        }

        /**
         *
         * @param \omise-php\OmiseCharge $charge
         * @return boolean
         */
        public static function isChargeObject($charge)
        {
            if (! isset($charge['object']) || $charge['object'] !== 'charge') {
                return false;
            }

            return true;
        }

        /**
         * Check whether the current currency is supported by the Omise API.
         * (This could/should probably come from Capbilities API in the future)
         *
         * Now, Omise API has no interface to check the supported currencies.
         * So, the supported currencies have been fixed in this function.
         *
         * @param string $currency
         * @return bool
         */
        public static function isCurrentCurrencyApplicable($currency)
        {
            $curr = strtoupper($currency);
            return in_array($curr, self::$supportedCurrencies);
        }

        /**
         *
         * @param \omise-php\OmiseCharge $charge
         * @return boolean
         */
        public static function isFailed($charge)
        {
            if (! self::isChargeObject($charge)) {
                return true;
            }

            if ((! is_null($charge['failure_code']) && $charge['failure_code'] !== "")
                || (! is_null($charge['failure_message']) && $charge['failure_message'] !== "")) {
                return true;
            }

            if (strtoupper($charge['status']) === 'FAILED') {
                return true;
            }

            return false;
        }

        /**
         *
         * @param \omise-php\OmiseCharge $charge
         * @return boolean
         */
        public static function isPaid($charge)
        {
            if (! self::isChargeObject($charge)) {
                return false;
            }

            // support Omise API version '2014-07-27' by checking if 'captured' exist.
            $paid = isset($charge['captured']) ? $charge['captured'] : $charge['paid'];
            if ($paid === true) {
                return true;
            }

            return false;
        }
    }
}
