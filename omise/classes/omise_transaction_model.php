<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

class OmiseTransactionModel extends ObjectModel
{
    /** @var int */
    public $id_order;

    /** @var string */
    public $id_charge;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'omise_transaction',
        'primary' => 'id_omise_transaction',
        'fields' => array(
            'id_order' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_charge' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true, 'size' => 255),
        ),
    );

    /**
     * Create a database table that will be used to store the reference between PrestaShop order ID and Omise charge ID.
     *
     * @return bool
     */
    public function createTable()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'omise_transaction` (
			`id_omise_transaction` int(10) unsigned NOT NULL auto_increment,
			`id_order` int(10) unsigned NOT NULL,
			`id_charge` varchar(255) NOT NULL,
			PRIMARY KEY (`id_omise_transaction`)
			) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

        return Db::getInstance()->execute($sql);
    }

    /**
     * Return an Omise charge ID by searching from a given parameter, PrestaShop order ID.
     *
     * If an Omise charge ID is not found, the false value will be returned.
     *
     * @param $id_order
     *
     * @return string|false
     */
    public function getIdCharge($id_order)
    {
        $query = new DbQuery();
        $query->select('id_charge');
        $query->from('omise_transaction');
        $query->where('id_order = \'' . pSQL($id_order) . '\'');

        $result = Db::getInstance()->getRow($query);

        if (! $result
            || empty($result)
            || ! array_key_exists('id_charge', $result)
        ) {
            return false;
        }

        return $result['id_charge'];
    }

    /**
     * Return a PrestaShop order ID by searching from a given parameter, Omise charge ID.
     *
     * @param string $id_charge An Omise charge ID.
     *
     * @return int|false A PrestaShop order ID or false, if a PrestaShop order ID is not found.
     */
    public function getIdOrder($id_charge)
    {
        $query = new DbQuery();
        $query->select('id_order');
        $query->from('omise_transaction');
        $query->where('id_charge = \'' . pSQL($id_charge) . '\'');

        $result = Db::getInstance()->getRow($query);

        if (! $result
            || empty($result)
            || ! array_key_exists('id_order', $result)
        ) {
            return false;
        }

        return $result['id_order'];
    }
}
