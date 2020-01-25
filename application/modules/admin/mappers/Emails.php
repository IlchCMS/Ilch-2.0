<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Mappers;

use Modules\Admin\Models\Emails as EmailsModel;

class Emails extends \Ilch\Mapper
{
    /**
     * Gets all Modules with emails.
     *
     * @return EmailsModel[]|array
     */
    public function getEmailsModule()
    {
        $array = $this->db()->select('moduleKey')
            ->from('emails')
            ->order(['moduleKey' => 'DESC'])
            ->group(['moduleKey'])
            ->execute()
            ->fetchRows();

        if (empty($array)) {
            return null;
        }

        $emailsModule = [];
        foreach ($array as $entries) {
            $emailsModel = new EmailsModel();
            $emailsModel->setModuleKey($entries['moduleKey']);
            $emailsModule[] = $emailsModel;
        }

        return $emailsModule;
    }

    /**
     * Gets all Emails.
     * @param string $key
     * @param string $locale
     *
     * @return EmailsModel[]|array
     */
    public function getEmailsByKey($key, $locale)
    {
        $array = $this->db()->select('*')
            ->from('emails')
            ->where(['moduleKey' => $key, 'locale' => $locale])
            ->execute()
            ->fetchRows();

        if (empty($array)) {
            return null;
        }

        $emails = [];
        foreach ($array as $entries) {
            $emailsModel = new EmailsModel();
            $emailsModel->setType($entries['type']);
            $emailsModel->setDesc($entries['desc']);
            $emailsModel->setLocale($entries['locale']);
            $emails[] = $emailsModel;
        }

        return $emails;
    }

    /**
     * Gets all Emails.
     * @param string $key
     * @param string $type
     * @param string $locale
     *
     * @return EmailsModel[]|array
     */
    public function getEmailsByKeyTypeLocale($key, $type, $locale)
    {
        $array = $this->db()->select('*')
            ->from('emails')
            ->where(['moduleKey' => $key, 'type' => $type, 'locale' => $locale])
            ->execute()
            ->fetchRows();

        if (empty($array)) {
            return null;
        }

        $emails = [];
        foreach ($array as $entries) {
            $emailsModel = new EmailsModel();
            $emailsModel->setType($entries['type']);
            $emailsModel->setDesc($entries['desc']);
            $emailsModel->setLocale($entries['locale']);
            $emails[] = $emailsModel;
        }

        return $emails;
    }

    /**
     * Get the Email.
     * @param string $moduleKey
     * @param string $type
     * @param string $locale
     *
     * @return EmailsModel|null
     */
    public function getEmail($moduleKey, $type, $locale)
    {
        $showEntry = $this->db()->select('*')
            ->from('emails')
            ->where(['moduleKey' => $moduleKey, 'type' => $type, 'locale' => $locale])
            ->execute()
            ->fetchAssoc();

        if ($showEntry) {
            $result = $this->db()->select('*')
                ->from('emails')
                ->where(['moduleKey' => $moduleKey, 'type' => $type, 'locale' => $locale])
                ->execute()
                ->fetchAssoc();
        } else {
            $result = $this->db()->select('*')
                ->from('emails')
                ->where(['moduleKey' => $moduleKey, 'type' => $type, 'locale' => 'de_DE'])
                ->execute()
                ->fetchAssoc();
        }

        if (empty($result)) {
            return null;
        }

        $emailsModel = new EmailsModel();
        $emailsModel->setModuleKey($result['moduleKey']);
        $emailsModel->setType($result['type']);
        $emailsModel->setDesc($result['desc']);
        $emailsModel->setText($result['text']);
        $emailsModel->setLocale($result['locale']);

        return $emailsModel;
    }

    /**
     * Inserts or Update the Email.
     *
     * @param EmailsModel $email
     */
    public function save(EmailsModel $email)
    {
        $fields = [
            'moduleKey' => $email->getModuleKey(),
            'type' => $email->getType(),
            'desc' => $email->getDesc(),
            'text' => $email->getText(),
            'locale' => $email->getLocale()
        ];

        $row = $this->db()->select('*')
            ->from('emails')
            ->where(['moduleKey' => $email->getModuleKey(), 'type' => $email->getType(), 'locale' => $email->getLocale()])
            ->execute()
            ->fetchAssoc();

        if ($row) {
            $this->db()->update('emails')
                ->values($fields)
                ->where(['moduleKey' => $email->getModuleKey(), 'type' => $email->getType(), 'locale' => $email->getLocale()])
                ->execute();
        } else {
            $this->db()->insert('emails')
                ->values($fields)
                ->execute();
        }
    }
}
