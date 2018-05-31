<?php
if (! class_exists('OmisePluginHelperCharge')) {
    class OmisePluginHelperCharge
    {
        /**
         *
         * @param string $currency
         * @param integer $amount
         * @return string
         */
        public static function amount($currency, $amount)
        {
            switch (strtoupper($currency)) {
                case 'GBP':
                case 'EUR':
                case 'USD':
                case 'SGD':
                case 'THB':
                    $amount = $amount * 100;
                    break;
            }

            return $amount;
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
         *
         * Now, Omise API has no interface to check the supported currencies.
         * So, the supported currencies have been fixed in this function.
         *
         * @param string $currency
         * @return bool
         */
        public static function isCurrentCurrencyApplicable($currency)
        {
            switch ($currency) {
                case 'GBP':
                case 'EUR':
                case 'USD':
                case 'JPY':
                case 'SGD':
                case 'THB':
                    return true;
            }

            return false;
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
