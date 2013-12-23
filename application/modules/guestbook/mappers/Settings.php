<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Guestbook\Mappers;

use Guestbook\Models\Settings as SettingsModel;

defined('ACCESS') or die('no direct access');

class Settings extends \Ilch\Mapper
{
    /**
     * Gets all new entries.
     *
     * @return Guestbook\Models\Settings[]|null
     */
    public function getNewEntries()
    {
        $sql = 'SELECT *
                FROM [prefix]_gbook
                WHERE setfree = 1
                ORDER by id DESC';
        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
        }

        $entry = array();

        foreach ($entryArray as $entries) {
            $entryModel = new SettingsModel();
            $entryModel->setId($entries['id']);
            $entryModel->setEmail($entries['email']);
            $entryModel->setText($entries['text']);
            $entryModel->setDatetime($entries['datetime']);
            $entryModel->setHomepage($entries['homepage']);
            $entryModel->setName($entries['name']);
            $entryModel->setFree($entries['setfree']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Gets one guestbook setting.
     *
     * @param string $cell
     * @return mixed
     */
    public function getSettings($cell)
    {
        return $this->db()->selectCell($cell, 'gbook_settings');
    }

    /**
     * Gets all guestbook settings
     *
     * @return Guestbook\Models\Settings[]
     */
    public function getAllSettings()
    {
        $settingsArray = $this->db()->selectArray('*', 'gbook_settings');

        $entrySettings = array();

        foreach ($settingsArray as $entries) {
            $entryModel = new SettingsModel();
            $entryModel->setEntrySettings($entries['entrysettings']);
            $entrySettings[] = $entryModel;
        }

        return $entrySettings;
    }

    /**
     * Update guestbook settings.
     *
     * @param array $datas
     * @return boolean
     */
    public function saveSettings(array $datas)
    {
        return $this->db()->update($datas, 'gbook_settings');
    }

    public function saveSetfree(array $datas, array $id)
    {
       return $this->db()->update($datas, 'gbook', $id);
    }
}
