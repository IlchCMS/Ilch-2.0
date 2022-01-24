<?php
/**
 * @copyright Ilch 2
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
    public function __construct(\Ilch\Database\Mysql $db)
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
    public function get(string $key, bool $alwaysLoad = false)
    {
        if (isset($this->configData[$key]['value']) && !$alwaysLoad) {
            return $this->configData[$key]['value'];
        }

        $configRow = $this->db->select(['value', 'key', 'autoload'])
            ->from('config')
            ->where(['key' => $key])
            ->execute()
            ->fetchAssoc();

        if (empty($configRow)) {
            return null;
        }

        $this->configData[$key]['value'] = $configRow['value'];
        $this->configData[$key]['autoload'] = $configRow['autoload'];

        return $configRow['value'];
    }

    /**
     * Sets the config for given key/vale.
     *
     * @param string         $key
     * @param string|integer $value
     * @param integer        $autoload
     *
     * @return $this
     */
    public function set(string $key, $value, int $autoload = 0)
    {
        $oldValue = $this->db->select('value')
            ->from('config')
            ->where(['key' => $key])
            ->execute()
            ->fetchCell();

        if ($oldValue !== null) {
            if ($value !== $oldValue) {
                $this->db->update('config')
                    ->values([
                            'value' => $value,
                            'autoload' => $autoload])
                    ->where(['key' => $key])
                    ->execute();
            }
        } else {
            $this->db->insert('config')
                    ->values([
                        'key' => $key,
                        'value' => $value,
                        'autoload' => $autoload])
                    ->execute();
        }

        $this->configData[$key]['value'] = $value;
        $this->configData[$key]['autoload'] = $autoload;

        return $this;
    }

    /**
     * Loads the config from the database.
     */
    public function loadConfigFromDatabase()
    {
        $configs = $this->db->select(['key', 'value'])
            ->from('config')
            ->where(['autoload' => 1])
            ->execute()
            ->fetchRows();

        foreach ($configs as $config) {
            $this->configData[$config['key']]['value'] = $config['value'];
            $this->configData[$config['key']]['autoload'] = 1;
        }
    }

    /**
     * delete the config for given key.
     *
     * @param   string|array    $keys
     * @return  $this
     * @since   2.1.43
     */
    public function delete($keys)
    {
        if (is_array($keys)) {
            foreach ($keys as $key) {
                $this->deleteKey($key);
            }
        } else {
            $this->deleteKey($keys);
        }

        return $this;
    }

    /**
     * delete the config for given key.
     *
     * @param   string  $key
     * @return  boolean
     * @since   2.1.43
     */
    protected function deleteKey(string $key)
    {
        if (isset($this->configData[$key]['value'])) {
            unset($this->configData[$key]);
        }

        return (bool)$this->db->delete('config')
            ->where(['key' => $key])
            ->execute();
    }
}
