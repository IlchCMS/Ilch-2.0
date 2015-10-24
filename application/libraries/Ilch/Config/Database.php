<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Config;

class Database
{
    /**
     * @var \Ilch\Database\Mysql
     */
    private $db;

    /**
     * @var array
     */
    protected $configData;

    /**
     * Injects database adapter to config.
     *
     * @param \Ilch\Database\Mysql $db
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
            $configRow = $this->db->select(array('value', 'key', 'autoload'))
                ->from('config')
                ->where(array('key' => $key))
                ->execute()
                ->fetchAssoc();

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
        $oldValue = $this->db->select('value')
            ->from('config')
            ->where(array('key' => $key))
            ->execute()
            ->fetchCell();

        if ($oldValue !== null) {
            if ($value !== $oldValue) {
                $this->db->update('config')
                    ->values(array(
                            'value' => $value,
                            'autoload' => $autoload))
                    ->where(array('key' => $key))
                    ->execute();
            }
        } else {
                $this->db->insert('config')
                    ->values(array(
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
        $configs = $this->db->select(array('key', 'value'))
            ->from('config')
            ->where(array('autoload' => 1))
            ->execute()
            ->fetchRows();

        foreach ($configs as $config) {
            $this->configData[$config['key']]['value'] = $config['value'];
            $this->configData[$config['key']]['autoload'] = 1;
        }
    }
}
