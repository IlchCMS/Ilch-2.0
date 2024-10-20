<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Cookieconsent\Config;

class Config extends \Ilch\Config\Install
{
    public array $config = [
        'key' => 'cookieconsent',
        'icon_small' => 'fa-solid fa-section',
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
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('cookie_consent', '1')
            ->set('cookie_consent_pos', 'top')
            ->set('cookie_icon_pos', 'TopLeft')
            ->set('cookie_consent_popup_bg_color', '#000000')
            ->set('cookie_consent_popup_text_color', '#ffffff')
            ->set('cookie_consent_btn_bg_color', '#f1d600')
            ->set('cookie_consent_btn_text_color', '#00000');
    }

    public function getInstallSql(): string
    {
        return '';
    }

    public function getUpdate(string $installedVersion): string
    {
        switch ($installedVersion) {
            case "2.1.8":
                // Delete now unneeded table
                $this->db()->query('DROP TABLE `[prefix]_cookies_consent`');

                // Delete unneeded files and folders
                unlink(ROOT_PATH . '/application/modules/cookieconsent/controllers/Index.php');
                unlink(ROOT_PATH . '/application/modules/cookieconsent/controllers/admin/Settings.php');
                removeDir(ROOT_PATH . '/application/modules/cookieconsent/mappers');
                removeDir(ROOT_PATH . '/application/modules/cookieconsent/models');
                removeDir(ROOT_PATH . '/application/modules/cookieconsent/views/index');
                removeDir(ROOT_PATH . '/application/modules/cookieconsent/views/admin/settings');
                break;
            case "2.1.60":
                $this->db()->query("UPDATE `[prefix]_modules` SET `icon_small` = '" . $this->config['icon_small'] . "' WHERE `key` = '" . $this->config['key'] . "';");
                break;
            case "2.2.4":
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->set('cookie_icon_pos', 'TopLeft');
                $databaseConfig->delete('cookie_consent_layout');
                if ($databaseConfig->get('cookie_consent_pos') == 'bottom-left' || $databaseConfig->get('cookie_consent_pos') == 'bottom-right') {
                    $databaseConfig->set('cookie_consent_pos', 'bottom');
                }
                removeDir(ROOT_PATH . '/static/js/cookieconsent');
                break;
        }

        return '"' . $this->config['key'] . '" Update-function executed.';
    }
}
