<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Cookieconsent\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'cookieconsent',
        'icon_small' => 'fa-paragraph',
        'system_module' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'Cookie-Richtlinien',
                'description' => 'Hier können die Cookie-Richtlinien verwaltet werden.',
            ],
            'en_EN' => [
                'name' => 'Cookie Consent',
                'description' => 'Here you can manage the cookie consent.',
            ],
        ]
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('cookie_consent', '1')
            ->set('cookie_consent_layout', 'classic')
            ->set('cookie_consent_pos', 'top')
            ->set('cookie_consent_popup_bg_color', '#000000')
            ->set('cookie_consent_popup_text_color', '#ffffff')
            ->set('cookie_consent_btn_bg_color', '#f1d600')
            ->set('cookie_consent_btn_text_color', '#00000');
    }

    public function getInstallSql()
    {

    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "2.1.8":
                // Delete now unneeded table
                $this->db()->query('DROP TABLE `[prefix]_cookies_consent`');

                // Delete unneeded files and folders
                unlink(ROOT_PATH.'/application/modules/cookieconsent/controllers/Index.php');
                unlink(ROOT_PATH.'/application/modules/cookieconsent/controllers/admin/Settings.php');
                removeDir(ROOT_PATH.'/application/modules/cookieconsent/mappers');
                removeDir(ROOT_PATH.'/application/modules/cookieconsent/models');
                removeDir(ROOT_PATH.'/application/modules/cookieconsent/views/index');
                removeDir(ROOT_PATH.'/application/modules/cookieconsent/views/admin/settings');
        }
    }
}
