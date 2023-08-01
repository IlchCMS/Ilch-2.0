<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Bbcodeconvert\Controllers\Admin;

use Ilch\Controller\Admin;
use Modules\Admin\Mappers\Module as ModuleMapper;
use Modules\Bbcodeconvert\Mappers\Texts as TextsMapper;
use Modules\Bbcodeconvert\Mappers\User as UserMapper;
use Modules\Admin\Mappers\Backup as BackupMapper;
use Modules\Admin\Models\Layout as LayoutModel;
use Modules\Admin\Mappers\LayoutAdvSettings;

class Index extends Admin
{
    private const supportedModules = [
        'contact' => ['2.1.52'],
        'events' => ['1.22.0'],
        'forum' => ['1.33.0'],
        'guestbook' => ['1.13.0'],
        'jobs' => ['1.6.0'],
        'teams' => ['1.23.0'],
        'user' => ['2.1.52']
    ];

    private const supportedLayouts = [
        'privatlayout' => ['1.1.0']
    ];

    // Number of items per batch (work is splitted up).
    private const batch = 100;

    // Worst case character limits for utf8mb4 (all asian characters or emojis). An utf8mb4 character set can require up to four bytes per character.
    // L: the byte length of the string
    // L + 2 bytes, where L < 2^16 (65,535 / 4 = 16,383)
    // L + 3 bytes, where L < 2^24 (16,777,215 / 4 = 4,194,303)
    // L + 2 bytes, where L < 2^32 (4,294,967,295 / 4 = 1,073,741,823)
    private const limitText = 16383;
    private const limitMediumText = 4194303;
    private const limitLongText = 1073741823;

    public function init()
    {
        $items = [
            [
                'name' => 'menuOverview',
                'active' => false,
                'icon' => 'fa-solid fa-arrow-right-arrow-left',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuNote',
                'active' => false,
                'icon' => 'fa-solid fa-circle-info',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'note'])
            ]
        ];

        if ($this->getRequest()->getActionName() === 'note') {
            $items[1]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'menuConvert',
            $items
        );
    }

    public function indexAction()
    {
        $moduleMapper = new ModuleMapper();
        $backupMapper = new BackupMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuConvert'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuOverview'), ['action' => 'index']);

        $installedSupportedModules = [];
        $installedSupportedLayouts = [];
        $_SESSION['bbcodeconvert_toConvert'] = [];
        $modules = $moduleMapper->getModules();
        $getHtmlFromBBCodeExists = method_exists('\Ilch\View', 'getHtmlFromBBCode');

        foreach ($modules as $module) {
            // Check if the version of the module is supported. For system modules this is the ilch version.
            if (array_key_exists($module->getKey(), self::supportedModules) && (in_array($module->getVersion(), self::supportedModules[$module->getKey()]) || ($module->getSystemModule() && in_array(VERSION, self::supportedModules[$module->getKey()])))) {
                if ($module->getSystemModule() && empty($module->getVersion())) {
                    $module->setVersion(VERSION);
                }

                $installedSupportedModules[] = $module;
            }
        }

        foreach (glob(APPLICATION_PATH.'/layouts/*') as $layoutPath) {
            file_put_contents('php://stderr', print_r($layoutPath.PHP_EOL, TRUE));
            if (is_dir($layoutPath)) {
                file_put_contents('php://stderr', print_r('is_dir true'.PHP_EOL, TRUE));
                $configClass = '\\Layouts\\' . ucfirst(basename($layoutPath)) . '\\Config\\Config';
                $config = new $configClass($this->getTranslator());
                $model = new LayoutModel();
                $model->setKey(basename($layoutPath));
                $model->setName($config->config['name']);
                $model->setVersion($config->config['version']);

                // Check if the version of the layout is supported.
                if (array_key_exists($model->getKey(), self::supportedLayouts) && (in_array($model->getVersion(), self::supportedLayouts[$model->getKey()]))) {
                    $installedSupportedLayouts[] = $model;
                }
            }
        }

        if ($getHtmlFromBBCodeExists && $this->getRequest()->getPost('action') === 'convert' && ($this->getRequest()->getPost('check_modules') || $this->getRequest()->getPost('check_layouts'))) {
            if ($this->getRequest()->getPost('check_modules')) {
                foreach ($this->getRequest()->getPost('check_modules') as $moduleKey) {
                    $_SESSION['bbcodeconvert_toConvert'][] = ['key' => $moduleKey, 'currentTask' => '', 'completed' => false, 'index' => 0, 'progress' => 0, 'count' => $this->getCount($moduleKey)];
                }
            }

            if ($this->getRequest()->getPost('check_layouts')) {
                foreach ($this->getRequest()->getPost('check_layouts') as $layoutKey) {
                    $_SESSION['bbcodeconvert_toConvert'][] = ['key' => $layoutKey, 'currentTask' => '', 'completed' => false, 'index' => 0, 'progress' => 0, 'count' => $this->getCount($layoutKey)];
                }
            }

            $this->redirect($this->getView()->getUrl(['action' => 'convert'], null, true));
        }

        $this->getView()->set('installedSupportedModules', $installedSupportedModules)
            ->set('installedSupportedLayouts', $installedSupportedLayouts)
            ->set('maintenanceModeEnabled', $this->getConfig()->get('maintenance_mode'))
            ->set('lastBackup', $backupMapper->getLastBackup())
            ->set('converted', (json_decode($this->getConfig()->get('bbcodeconvert_converted'), true)) ?? [])
            ->set('getHtmlFromBBCodeExists', $getHtmlFromBBCodeExists);
    }

    public function convertAction()
    {
        if (!$this->getRequest()->isSecure() || !method_exists('\Ilch\View', 'getHtmlFromBBCode')) {
            $this->redirect(['action' => 'index']);
            return;
        }

        @set_time_limit(300);
        $workDone = true;

        foreach ($_SESSION['bbcodeconvert_toConvert'] as $key => $moduleOrLayout) {
            if (!$moduleOrLayout['completed']) {
                $workDone = false;
                $result = $this->convert($moduleOrLayout['key'], $moduleOrLayout['index'], $moduleOrLayout['progress']);

                if (!empty($result)) {
                    $_SESSION['bbcodeconvert_toConvert'][$key] = array_merge($_SESSION['bbcodeconvert_toConvert'][$key], $this->convert($moduleOrLayout['key'], $moduleOrLayout['index'], $moduleOrLayout['progress'], $moduleOrLayout['currentTask']));

                    // Exit this loop to not reach max_execution_time inside this function. This function gets called again by a javascript redirect.
                    $this->getView()->set('redirectAfterPause', true);
                    break;
                }
            }
        }

        $converted = [];
        foreach($_SESSION['bbcodeconvert_toConvert'] as $value) {
            $converted[$value['key']] = $value['completed'];
        }

        $knownConverted = (json_decode($this->getConfig()->get('bbcodeconvert_converted'), true)) ?? [];
        $knownConverted = array_merge($knownConverted, $converted);
        $this->getConfig()->set('bbcodeconvert_converted', json_encode($knownConverted));

        $this->getView()->set('workDone', $workDone);
    }

    public function noteAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuConvert'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuNote'), ['action' => 'note']);

        $this->getView()->set('supportedModules', self::supportedModules)
            ->set('supportedLayouts', self::supportedLayouts);
    }

    /**
     * Get number of items to convert.
     *
     * @param string $key
     * @return int
     */
    public function getCount(string $key): int
    {
        $textsMapper = new TextsMapper();

        switch ($key) {
            case 'contact':
                // table: config, column: value, datatype: VARCHAR(191)
                return 1;
            case 'events':
                // table: events, column: text, datatype: LONGTEXT
                $textsMapper->table = 'events';
                break;
            case 'forum':
                // table: forum_posts, column: text, datatype: TEXT
                $textsMapper->table = 'forum_posts';
                break;
            case 'guestbook':
                // table: gbook, column: text, datatype: MEDIUMTEXT
                $textsMapper->table = 'gbook';
                break;
            case 'jobs':
                // table: jobs, column: text, datatype: MEDIUMTEXT
                $textsMapper->table = 'jobs';
                break;
            case 'teams':
                // table: teams_joins, column: text, datatype: LONGTEXT
                $textsMapper->table = 'teams_joins';
                break;
            case 'user':
                // table: users, column: signature, datatype: VARCHAR
                // table: users_dialog_reply, column: reply, datatype: TEXT
                $userMapper = new UserMapper();

                return $userMapper->getCount();
            case 'privatlayout':
                // Layout
                // table: admin_layoutadvsettings, column: value, datatype: TEXT
                return 1;
        }

        return $textsMapper->getCount();
    }

    /**
     * Convert from bbcode to html.
     *
     * @param string $key
     * @param int $index
     * @param int $progress
     * @param string $currentTask
     * @return array
     * @see https://dev.mysql.com/doc/refman/8.0/en/storage-requirements.html#data-types-storage-reqs-strings
     */
    public function convert(string $key, int $index, int $progress, string $currentTask = ''): array
    {
        switch ($key) {
            case 'contact':
                // table: config, column: value, datatype: TEXT
                $convertedText = $this->getView()->getHtmlFromBBCode($this->getConfig()->get('contact_welcomeMessage'));

                if (strlen($convertedText) <= self::limitText) {
                    $this->getConfig()->set('contact_welcomeMessage', $convertedText);
                }
                return ['completed' => true, 'index' => 0, 'progress' => 1];
            case 'events':
                // table: events, column: text, datatype: LONGTEXT
                $textsMapper = new TextsMapper();
                $textsMapper->table = 'events';
                $texts = $textsMapper->getTexts($index, self::batch);

                foreach($texts as $text) {
                    $convertedText = $this->getView()->getHtmlFromBBCode($text['text']);

                    if (strlen($convertedText) <= self::limitLongText) {
                        $textsMapper->updateText($text['id'], $convertedText);
                    }
                }

                if (empty($texts)) {
                    return ['completed' => true, 'index' => $index];
                }

                return ['completed' => false, 'index' => $index + count($texts), 'progress' => $index + count($texts)];
            case 'forum':
                // table: forum_posts, column: text, datatype: TEXT
                $textsMapper = new TextsMapper();
                $textsMapper->table = 'forum_posts';
                $texts = $textsMapper->getTexts($index, self::batch);

                foreach($texts as $text) {
                    $convertedText = $this->getView()->getHtmlFromBBCode($text['text']);

                    if (strlen($convertedText) <= self::limitText) {
                        $textsMapper->updateText($text['id'], $convertedText);
                    }
                }

                if (empty($texts)) {
                    return ['completed' => true, 'index' => $index];
                }

                return ['completed' => false, 'index' => $index + count($texts), 'progress' => $index + count($texts)];
            case 'guestbook':
                // table: gbook, column: text, datatype: MEDIUMTEXT
                $textsMapper = new TextsMapper();
                $textsMapper->table = 'gbook';
                $texts = $textsMapper->getTexts($index, self::batch);

                foreach($texts as $text) {
                    $convertedText = $this->getView()->getHtmlFromBBCode($text['text']);

                    if (strlen($convertedText) <= self::limitMediumText) {
                        $textsMapper->updateText($text['id'], $convertedText);
                    }
                }

                if (empty($texts)) {
                    return ['completed' => true, 'index' => $index];
                }

                return ['completed' => false, 'index' => $index + count($texts), 'progress' => $index + count($texts)];
            case 'jobs':
                // table: jobs, column: text, datatype: MEDIUMTEXT
                $textsMapper = new TextsMapper();
                $textsMapper->table = 'jobs';
                $texts = $textsMapper->getTexts($index, self::batch);

                foreach($texts as $text) {
                    $convertedText = $this->getView()->getHtmlFromBBCode($text['text']);

                    if (strlen($convertedText) <= self::limitMediumText) {
                        $textsMapper->updateText($text['id'], $convertedText);
                    }
                }

                if (empty($texts)) {
                    return ['completed' => true, 'index' => $index];
                }

                return ['completed' => false, 'index' => $index + count($texts), 'progress' => $index + count($texts)];
            case 'teams':
                // table: teams_joins, column: text, datatype: LONGTEXT
                $textsMapper = new TextsMapper();
                $textsMapper->table = 'teams_joins';
                $texts = $textsMapper->getTexts($index, self::batch);

                foreach($texts as $text) {
                    $convertedText = $this->getView()->getHtmlFromBBCode($text['text']);

                    if (strlen($convertedText) <= self::limitLongText) {
                        $textsMapper->updateText($text['id'], $convertedText);
                    }
                }

                if (empty($texts)) {
                    return ['completed' => true, 'index' => $index];
                }

                return ['completed' => false, 'index' => $index + count($texts), 'progress' => $index + count($texts)];
            case 'user':
                $userMapper = new UserMapper();

                if ($currentTask === '' || $currentTask === 'signature') {
                    // table: users, column: signature, datatype: VARCHAR(255)
                    $signatures = $userMapper->getSignatures($index, self::batch);

                    foreach($signatures as $signature) {
                        $convertedSignature = $this->getView()->getHtmlFromBBCode($signature['signature']);

                        if (strlen($convertedSignature) <= 255) {
                            $userMapper->updateSignature($signature['id'], $convertedSignature);
                        }
                    }

                    if (!empty($signatures) && $currentTask === 'signature') {
                        return ['currentTask' => 'signature', 'completed' => false, 'index' => $index + count($signatures), 'progress' => $index + count($signatures)];
                    }

                    if (empty($signatures)) {
                        // Reset index to 0 for next task.
                        $index = 0;
                    }
                }

                // table: users_dialog_reply, column: reply, datatype: TEXT
                $replies = $userMapper->getReplies($index, self::batch);

                foreach($replies as $reply) {
                    $convertedReply = $this->getView()->getHtmlFromBBCode($reply['reply']);

                    if (strlen($convertedReply) <= self::limitText) {
                        $userMapper->updateReply($reply['cr_id'], $convertedReply);
                    }
                }

                if (empty($replies)) {
                    return ['currentTask' => 'reply', 'completed' => true, 'index' => $index];
                }

                return ['currentTask' => 'reply', 'completed' => false, 'index' => $index + count($replies), 'progress' => $progress + count($replies)];
            case 'privatlayout':
                // Layout
                // table: admin_layoutadvsettings, column: value, datatype: TEXT
                $layoutAdvSettingsMapper = new LayoutAdvSettings();

                $model[] = $layoutAdvSettingsMapper->getSetting('privatlayout', 'siteInfo');
                $model[0]->setValue(nl2br($this->getView()->getHtmlFromBBCode($model[0]->getValue())));

                if (strlen($model[0]->getValue()) <= self::limitText) {
                    $layoutAdvSettingsMapper->save($model);
                }

                return ['completed' => true, 'index' => 0, 'progress' => 1];
        }

        return [];
    }
}
