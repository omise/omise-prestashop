<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

class OmiseCustomerModel extends ObjectModel
{
    /** @var int */
    public $id_prestashop_customer;

    /** @var string Omise customer ID belong to Omise test account */
    public $id_omise_test_customer;

    /** @var string Omise customer ID belong to Omise live account */
    public $id_omise_live_customer;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'omise_customer',
        'primary' => 'id_prestashop_customer',
        'fields' => array(
            'id_prestashop_customer' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_omise_test_customer' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => false, 'size' => 255),
            'id_omise_live_customer' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => false, 'size' => 255),
        ),
    );

    /**
     * Create a database table that will be used to store the reference between PrestaShop customer ID and Omise
     * customer ID.
     *
     * It is possible that a PrestaShop customer (payer) has two Omise customer ID.
     *
     * 1. The Omise customer ID belong to Omise TEST account.
     * 2. The Omise customer ID belong to Omise LIVE account.
     *
     * This situation can be happened by the merchant switching between their Omise test and live account on the Omise
     * configuration page, PrestaShop back office.
     *
     * @return bool
     */
    public function createTable()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'omise_customer` (
            `id_prestashop_customer` int(10) unsigned NOT NULL,
            `id_omise_test_customer` varchar(255) NULL,
            `id_omise_live_customer` varchar(255) NULL,
            PRIMARY KEY (`id_prestashop_customer`),
            UNIQUE KEY `id_omise_test_customer` (`id_omise_test_customer`),
            UNIQUE KEY `id_omise_live_customer` (`id_omise_live_customer`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

        return Db::getInstance()->execute($sql);
    }

    /**
     * @return bool
     */
    public function dropTable()
    {
        $sql = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'omise_customer`';

        return Db::getInstance()->execute($sql);
    }
}
