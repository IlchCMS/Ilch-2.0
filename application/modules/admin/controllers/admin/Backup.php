<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

use Modules\Admin\Mappers\Backup as BackupMapper;
use Modules\Admin\Models\Backup as BackupModel;
use Ilch\Config\File;
use Ifsnop\Mysqldump as IMysqldump;

class Backup extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ],
            [
                'name' => 'menuMaintenance',
                'active' => false,
                'icon' => 'fa-solid fa-wrench',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'maintenance'])
            ],
            [
                'name' => 'menuCustomCSS',
                'active' => false,
                'icon' => 'fa-regular fa-file-code',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'customcss'])
            ],
            [
                'name' => 'menuHtaccess',
                'active' => false,
                'icon' => 'fa-regular fa-file-code',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'htaccess'])
            ],
            [
                'name' => 'menuBackup',
                'active' => false,
                'icon' => 'fa-solid fa-download',
                'url' => $this->getLayout()->getUrl(['controller' => 'backup', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => $this->getLayout()->getUrl(['controller' => 'backup', 'action' => 'create'])
                ],
            ],
            [
                'name' => 'menuUpdate',
                'active' => false,
                'icon' => 'fa-solid fa-arrows-rotate',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'update'])
            ],
            [
                'name' => 'menuNotifications',
                'active' => false,
                'icon' => 'fa-regular fa-envelope',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'notifications'])
            ],
            [
                'name' => 'menuEmails',
                'active' => false,
                'icon' => 'fa-solid fa-envelope',
                'url' => $this->getLayout()->getUrl(['controller' => 'emails', 'action' => 'index'])
            ],
            [
                'name' => 'menuMail',
                'active' => false,
                'icon' => 'fa-regular fa-newspaper',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'mail'])
            ]
        ];

        if ($this->getRequest()->getActionName() === 'create') {
            $items[4][0]['active'] = true;
        } else {
            $items[4]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuSettings',
            $items
        );
    }

    public function indexAction()
    {
        $backupMapper = new BackupMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuSettings'), ['controller' => 'settings', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuBackup'), ['action' => 'index']);

        if ($this->getRequest()->getPost('id') && $this->getRequest()->getPost('action') === 'delete') {
            foreach ($this->getRequest()->getPost('id') as $backupId) {
                $backupMapper->delete($backupId);
            }
        }

        $this->getView()->set('backups', $backupMapper->getBackups());
    }

    public function createAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuSettings'), ['controller' => 'settings', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuBackup'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('add'), ['action' => 'create']);

        if ($this->getRequest()->isPost()) {
            $date = new \Ilch\Date();
            $dbDate = $date->format('Y-m-d H-i-s', true);
            $filename = $date->format('Y-m-d_H-i-s', true) . '_' . bin2hex(random_bytes(32));

            $fileConfig = new File();
            $fileConfig->loadConfigFromFile(CONFIG_PATH . '/config.php');
            $dbhost = $fileConfig->get('dbHost');
            $dbuser = $fileConfig->get('dbUser');
            $dbpassword = $fileConfig->get('dbPassword');
            $dbname = $fileConfig->get('dbName');
            $compressFile = '.sql';
            if ($this->getRequest()->getPost('compress') === 'gzip') {
                $compress = 'GZIP';
                $compressFile .= '.gz';
            } else {
                $compress = 'NONE';
            }

            $dumpfile = ROOT_PATH . '/backups/' . $dbname . '_' . $filename . $compressFile;
            $addDatabases = ($this->getRequest()->getPost('addDatabases') == 1);
            $dropTable = ($this->getRequest()->getPost('dropTable') == 1);
            $skipComments = ($this->getRequest()->getPost('skipComments') == 1);

            try {
                $dumpSettings = [
                    'compress' => $compress,
                    'add-drop-table' => $dropTable,
                    'lock-tables' => false,
                    'add-locks' => false,
                    'no-autocommit' => false,
                    'databases' => $addDatabases,
                    'skip-comments' => $skipComments
                ];

                $dump = new IMysqldump\Mysqldump('mysql:host=' . $dbhost . ';dbname=' . $dbname, $dbuser, $dbpassword, $dumpSettings);
                $dump->start($dumpfile);

                $backupMapper = new BackupMapper();
                $backupModel = new BackupModel();

                $backupModel->setName($dbname . '_' . $filename . $compressFile);
                $backupModel->setDate($dbDate);
                $backupMapper->save($backupModel);

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            } catch (\Exception $e) {
                echo 'mysqldump-php error: ' . $e->getMessage();
            }
        }
    }

    public function downloadAction()
    {
        if (!$this->getRequest()->isSecure()) {
            return;
        }

        set_time_limit(0); 
        $path = ROOT_PATH . '/backups/';

        $id = $this->getRequest()->getParam('id');

        if (!empty($id)) {
            $backupMapper = new BackupMapper();
            $backup = $backupMapper->getBackupById($id);

            if ($backup !== null) {
                $fullPath = $path . $backup->getName();
                if ($fd = fopen($fullPath, 'rb')) {
                    $path_parts = pathinfo($fullPath);
                    // Remove the random part of the filename as it should not end in e.g. the browser history.
                    $publicFileName = preg_replace('/_[^_.]*\./', ' . ', $path_parts['basename']);

                    if (strtolower($path_parts['extension']) === 'gz') {
                        header('Content-type: application/x-gzip');
                    } else {
                        header('Content-type: application/x-sql');
                    }
                    header('Content-Disposition: filename="' . $publicFileName. '"');
                    header('Content-length: ' . filesize($fullPath));
                    // RFC2616 section 14.9.1: Indicates that all or part of the response message is intended for a single user and MUST NOT be cached by a shared cache, such as a proxy server.
                    header('Cache-control: private');
                    while(!feof($fd)) {
                        $buffer = fread($fd, 2048);
                        echo $buffer;
                    }
                } else {
                    $this->addMessage('backupNotFound', 'danger');
                }
                fclose($fd);
            }
        }

        $this->redirect(['action' => 'index']);
    }

    public function importAction()
    {
        if (!$this->getRequest()->isSecure()) {
            return;
        }

        $id = $this->getRequest()->getParam('id');

        if (!empty($id)) {
            $fileConfig = new File();
            $backupMapper = new BackupMapper();

            $fileConfig->loadConfigFromFile(CONFIG_PATH . '/config.php');
            $dbhost = $fileConfig->get('dbHost');
            $dbuser = $fileConfig->get('dbUser');
            $dbpassword = $fileConfig->get('dbPassword');
            $dbname = $fileConfig->get('dbName');
            $backup = $backupMapper->getBackupById($id);

            if ($backup !== null) {
                set_time_limit(0);
                $fullPath = ROOT_PATH . '/backups/' . $backup->getName();

                try {
                    $dump = new IMysqldump\Mysqldump('mysql:host=' . $dbhost . ';dbname=' . $dbname, $dbuser, $dbpassword);
                    $dump->restore($fullPath);

                    $this->addMessage('backupImportSuccess');
                } catch (\Exception $e) {
                    $this->addMessage('mysqldump-php error: ' . $e->getMessage(), 'danger');
                }
            }
        }

        $this->redirect(['action' => 'index']);
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $backupMapper = new BackupMapper();
            $backup = $backupMapper->getBackupById($this->getRequest()->getParam('id'));
            unlink(ROOT_PATH . '/backups/' . $backup->getName());
            $backupMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }

    public function refreshAction()
    {
        if ($this->getRequest()->isSecure()) {
            // Look for new backup files that have been uploaded to the backups directory.
            $backupMapper = new BackupMapper();
            $backups = $backupMapper->getBackups() ?? [];
            $directory = ROOT_PATH . '/backups/';
            $backupsInDirectory = glob($directory . '*.sql*');
            $newBackupFiles = [];

            foreach ($backupsInDirectory as $backupInDirectory) {
                $found = false;
                foreach($backups as $backup) {
                    if (strpos($backupInDirectory, $backup->getName()) !== false) {
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $newBackupFiles[] = $backupInDirectory;
                }
            }

            foreach ($newBackupFiles as $newBackupFile) {
                $backupFileInfo = pathinfo($newBackupFile);
                // Create a filename that is not longer than 255 chars.
                $newFilename = substr($backupFileInfo['filename'], 0, 254 - 64 - strlen('_.' . $backupFileInfo['extension']));
                $newFilename = $newFilename . '_' . bin2hex(random_bytes(32)) . '.' . $backupFileInfo['extension'];
                // Rename the file to add a secure random part to it's filename before adding it to the table of backups.
                if (rename($newBackupFile, $backupFileInfo['dirname'] . '/' . $newFilename)) {
                    $backupModel = new BackupModel();
                    $backupMapper = new BackupMapper();

                    $backupModel->setName($newFilename);
                    $backupModel->setDate('0000-00-00');
                    $backupMapper->save($backupModel);
                } else {
                    // Renaming the backup file failed.
                    $this->addMessage('backupRefreshError', 'danger');
                    $this->redirect(['action' => 'index']);
                }
            }

            $this->addMessage('backupRefreshSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
