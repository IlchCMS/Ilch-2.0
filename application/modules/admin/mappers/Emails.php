<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Mappers;

use Modules\Admin\Models\Emails as EmailsModel;

class Emails extends \Ilch\Mapper
{
    /**
     * Gets all modules with emails.
     *
     * @return EmailsModel[]|null
     */
    public function getEmailsModule(): ?array
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
     * Gets all emails for the given module key and locale.
     *
     * @param string $key
     * @param string $locale
     * @return EmailsModel[]|null
     */
    public function getEmailsByKey(string $key, string $locale): ?array
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
            $emailsModel->setModuleKey($entries['moduleKey']);
            $emailsModel->setType($entries['type']);
            $emailsModel->setDesc($entries['desc']);
            $emailsModel->setText($entries['text']);
            $emailsModel->setLocale($entries['locale']);
            $emails[] = $emailsModel;
        }

        return $emails;
    }

    /**
     * Gets all emails for the given module key, type and locale.
     *
     * @param string $key
     * @param string $type
     * @param string $locale
     * @return EmailsModel[]|null
     */
    public function getEmailsByKeyTypeLocale(string $key, string $type, string $locale): ?array
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
            $emailsModel->setModuleKey($entries['moduleKey']);
            $emailsModel->setType($entries['type']);
            $emailsModel->setDesc($entries['desc']);
            $emailsModel->setText($entries['text']);
            $emailsModel->setLocale($entries['locale']);
            $emails[] = $emailsModel;
        }

        return $emails;
    }

    /**
     * Gets the email for the given module key, type and locale.
     * Falls back to the "de_DE" locale if no row exists for the given locale.
     *
     * @param string $moduleKey
     * @param string $type
     * @param string $locale
     * @return EmailsModel|null
     */
    public function getEmail(string $moduleKey, string $type, string $locale): ?EmailsModel
    {
        $result = $this->db()->select('*')
            ->from('emails')
            ->where(['moduleKey' => $moduleKey, 'type' => $type, 'locale' => $locale])
            ->execute()
            ->fetchAssoc();

        if (!$result) {
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
     * Inserts or updates the email.
     *
     * @param EmailsModel $email
     */
    public function save(EmailsModel $email): void
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
