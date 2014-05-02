<?php
/**
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
    private $db;

    /**
     * @var array
     */
    protected $configData;

    /**
     * Injects database adapter to config.
     *
     * @param Ilch_Database_* $db
     */
    public function __construct($db)
    {
        $this->db = $db;
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
        if (isset($this->configData[$key]['value']) && !$alwaysLoad) {
            return $this->configData[$key]['value'];
        } else {
            $configRow = $this->db->selectRow(array('value', 'key', 'autoload'))
                ->from('config')
                ->where(array('key' => $key))
                ->execute();

            if (empty($configRow)) {
                return null;
            }

            $this->configData[$key]['value'] = $configRow['value'];
            $this->configData[$key]['autoload'] = $configRow['autoload'];

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
        $oldValue = $this->db->selectCell('value')
            ->from('config')
            ->where(array('key' => $key))
            ->execute();

        if ($oldValue !== null) {
            if ($value !== $oldValue) {
                $this->db->update('config')
                    ->fields(array(
                            'value' => $value,
                            'autoload' => $autoload))
                    ->where(array('key' => $key))
                    ->execute();
            }
        } else {
                $this->db->insert('config')
                    ->fields(array(
                        'key' => $key,
                        'value' => $value,
                        'autoload' => $autoload))
                    ->execute();
        }

        $this->configData[$key]['value'] = $value;
        $this->configData[$key]['autoload'] = $autoload;
    }

    /**
     * Loads the config from the database.
     */
    public function loadConfigFromDatabase()
    {
        $configs = $this->db->selectArray(array('key', 'value'))
            ->from('config')
            ->where(array('autoload' => 1))
            ->execute();

        foreach ($configs as $config) {
            $this->configData[$config['key']]['value'] = $config['value'];
            $this->configData[$config['key']]['autoload'] = 1;
        }
    }
}
