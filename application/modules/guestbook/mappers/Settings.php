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
    public function getnewEntries() 
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
    
    public function getSettings($cell) 
    {
        $table = 'gbook_settings';
        $query = $this->db()->selectCell($cell,$table);
       
        return $query;
    }
    
    public function getallSettings() 
    {
        $sql = 'SELECT *
                FROM [prefix]_gbook_settings';
        $settingsArray = $this->db()->queryArray($sql);
        
        $entrySettings = array();

        foreach ($settingsArray as $entries) {
            $entryModel = new SettingsModel();
            $entryModel->setentrySettings($entries['entrysettings']);
            $entrySettings[] = $entryModel;
        }
        
        return $entrySettings;
    }
    
    public function saveSettings(array $datas) 
    {
        $this->db()->update($datas, 'gbook_settings');
    }
    
    public function saveSetfree(array $datas,array $id) 
    {
        $this->db()->update($datas, 'gbook', $id);
    }

}
