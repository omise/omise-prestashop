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
                case 'THB':
                    $amount = $amount * 100;
                    break;
            }

            return $amount;
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
         *
         * @param \omise-php\OmiseCharge $charge
         * @return boolean
         */
        public static function isFailed($charge)
        {
            if (! self::isChargeObject($charge)) {
                return true;
            }

            if ((! is_null($charge['failure_code']) && $charge['failure_code'] !== '')
                || (! is_null($charge['failure_message']) && $charge['failure_message'] !== '')) {
                return true;
            }

            if (strtoupper($charge['status']) === 'FAILED') {
                return true;
            }

            return false;
        }
    }
}
