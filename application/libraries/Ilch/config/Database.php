<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Config;
defined('ACCESS') or die('no direct access');

class Database
{
    /**
     * @var Ilch_Database_*
     */
    private $_db;

    /**
     * @var array
     */
    protected $_configData;

    /**
     * Injects database adapter to config.
     *
     * @param Ilch_Database_* $db
     */
    public function __construct($db)
    {
        $this->_db = $db;
    }

    /**
     * Gets the config for given key.
     *
     * @param  string     $key
     * @param  boolean    $alwaysLoad
     * @return mixed|null
     */
    public function get($key, $alwaysLoad = false)
    {
        if (isset($this->_configData[$key]['value']) && !$alwaysLoad) {
            return $this->_configData[$key]['value'];
        } else {
            $configRow = $this->_db->selectRow
            (
                array('value', 'key', 'autoload'),
                'config',
                array('key' => $key)
            );

            if (empty($configRow)) {
                return null;
            }

            $this->_configData[$key]['value'] = $configRow['value'];
            $this->_configData[$key]['autoload'] = $configRow['autoload'];

            return $configRow['value'];
        }
    }

    /**
     * Sets the config for given key/vale.
     *
     * @param string         $key
     * @param string|integer $value
     * @param integer        $autoload
     */
    public function set($key, $value, $autoload = 0)
    {
        $oldValue = (string) $this->_db->selectCell
        (
            'value',
            'config',
            array('key' => $key)
        );

        if ($oldValue != null) {
            if ($value !== $oldValue) {

                $this->_db->update
                (
                    array
                    (
                        'value' => $value,
                        'autoload' => $autoload
                    ),
                    'config',
                    array
                    (
                        'key' => $key
                    )

                );
            }
        } else {
            $this->_db->insert
            (
                array
                (
                    'key' => $key,
                    'value' => $value,
                    'autoload' => $autoload
                ),
                'config'
            );
        }

        $this->_configData[$key]['value'] = $value;
        $this->_configData[$key]['autoload'] = $autoload;
    }

    /**
     * Loads the config from the database.
     */
    public function loadConfigFromDatabase()
    {
        $configs = $this->_db->selectArray
        (
            array('key', 'value'),
            'config',
            array('autoload' => 1)
        );

        foreach ($configs as $config) {
            $this->_configData[$config['key']]['value'] = $config['value'];
            $this->_configData[$config['key']]['autoload'] = 1;
        }
    }
}
