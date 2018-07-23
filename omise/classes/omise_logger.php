<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

class OmiseLogger
{
    const
        ERROR = 3,
        INFO = 1,
        MAJOR_ISSUE = 4,
        WARNING = 2
    ;

    /**
     * Add log message to PrestaShop database.
     * The added log message will be displayed in PrestaShop back office.
     *
     * @param string $message
     * @param int $severity
     *
     * @return bool
     *
     * @see PrestaShopLoggerCore::addLog()
     */
    public function add($message, $severity = OmiseLogger::INFO)
    {
        $allow_duplicate = true;
        $error_code = null;
        $object_id = null;
        $object_type = null;

        return Logger::addLog(
            $message,
            $severity,
            $error_code,
            $object_type,
            $object_id,
            $allow_duplicate
        );
    }
}
