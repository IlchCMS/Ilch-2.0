<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Newsletter\Mappers;

use Ilch\Database\Mysql\Result;
use Ilch\Mapper;
use Modules\Newsletter\Models\Newsletter as NewsletterModel;
use Modules\Newsletter\Models\Subscriber as SubscriberModel;

class Subscriber extends Mapper
{
    /**
     * Gets the Newsletter entries.
     *
     * @return NewsletterModel[]|array
     */
    public function getMail(): ?array
    {
        $entryArray = $this->db()->select('*')
                ->from('newsletter_mails')
                ->execute()
                ->fetchRows();

        if (empty($entryArray)) {
            return null;
        }

        $entry = [];

        foreach ($entryArray as $entries) {
            $entryModel = new SubscriberModel();
            $entryModel->setId($entries['id']);
            $entryModel->setEmail($entries['email']);
            $entryModel->setSelector($entries['selector']);
            $entryModel->setConfirmCode($entries['confirmCode']);
            $entryModel->setDoubleOptInDate($entries['doubleOptInDate']);
            $entryModel->setDoubleOptInConfirmed($entries['doubleOptInConfirmed']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Gets the Newsletter subscriber by the email.
     *
     * @param string $email
     * @return SubscriberModel|null
     */
    public function getSubscriberByEMail(string $email): ?SubscriberModel
    {
        $entryArray = $this->db()->select('*')
            ->from('newsletter_mails')
            ->where(['email' => $email])
            ->execute()
            ->fetchAssoc();

        if (empty($entryArray)) {
            return null;
        }

        $entryModel = new SubscriberModel();
        $entryModel->setId($entryArray['id']);
        $entryModel->setEmail($entryArray['email']);
        $entryModel->setSelector($entryArray['selector']);
        $entryModel->setConfirmCode($entryArray['confirmCode']);
        $entryModel->setDoubleOptInDate($entryArray['doubleOptInDate']);
        $entryModel->setDoubleOptInConfirmed($entryArray['doubleOptInConfirmed']);

        return $entryModel;
    }

    /**
     * Gets the Newsletter subscriber by the selector.
     *
     * @param string $selector
     * @return SubscriberModel|null
     */
    public function getSubscriberBySelector(string $selector): ?SubscriberModel
    {
        $entryArray = $this->db()->select('*')
                ->from('newsletter_mails')
                ->where(['selector' => $selector])
                ->execute()
                ->fetchAssoc();

        if (empty($entryArray)) {
            return null;
        }

        $entryModel = new SubscriberModel();
        $entryModel->setId($entryArray['id']);
        $entryModel->setEmail($entryArray['email']);
        $entryModel->setConfirmCode($entryArray['confirmCode']);
        $entryModel->setDoubleOptInDate($entryArray['doubleOptInDate']);
        $entryModel->setDoubleOptInConfirmed($entryArray['doubleOptInConfirmed']);

        return $entryModel;
    }

    /**
     * Gets the Newsletter mail entries.
     *
     * @param string $email
     * @return int
     */
    public function countEmails(string $email): int
    {
        return $this->db()->select('COUNT(*)')
                ->from('newsletter_mails')
                ->where(['email' => $email])
                ->execute()
                ->fetchCell();
    }

    /**
     * Inserts newsletter mail model.
     *
     * @param SubscriberModel $subscriber
     * @return int
     */
    public function saveEmail(SubscriberModel $subscriber): int
    {
        $values = [
            'email' => $subscriber->getEmail(),
            'selector' => $subscriber->getSelector(),
            'confirmCode' => $subscriber->getConfirmCode(),
            'doubleOptInDate' => $subscriber->getDoubleOptInDate(),
            'doubleOptInConfirmed' => $subscriber->getDoubleOptInConfirmed(),
        ];

        if ($subscriber->getId()) {
            return $this->db()->update('newsletter_mails')
                ->values($values)
                ->where(['id' => $subscriber->getId()])
                ->execute();
        } else {
            return $this->db()->insert('newsletter_mails')
                ->values($values)
                ->execute();
        }
    }

    /**
     * Deletes newsletter email with given email.
     *
     * @param string $email
     */
    public function deleteEmail(string $email)
    {
        $this->db()->delete('newsletter_mails')
                ->where(['email' => $email])
                ->execute();
    }

    /**
     * Deletes newsletter email with given selector.
     *
     * @param string $selector
     */
    public function deleteSubscriberBySelector(string $selector)
    {
        $this->db()->delete('newsletter_mails')
                ->where(['selector' => $selector])
                ->execute();
    }

    /**
     * Delete unconfirmed double opt-in entries that are older than 24 hours.
     *
     * @return Result|int
     */
    public function deleteOldUnconfirmedDoubleOptIn()
    {
        return $this->db()->delete('newsletter_mails')
            ->where(['doubleOptInDate <' => date('Y-m-d H:i:s', strtotime('-24 hours')), 'doubleOptInConfirmed' => 0])
            ->execute();
    }

    /**
     * Gets the Newsletter entries.
     *
     * @return NewsletterModel[]|array
     */
    public function getSendMailUser(): array
    {
        return $this->db()->select()
                ->fields(['nm.email', 'nm.selector'])
                ->from(['nm' => 'newsletter_mails'])
                ->join(['u' => 'users'], 'u.email = nm.email', 'LEFT', ['name' => 'u.name'])
                ->execute()
                ->fetchRows();
    }

    /**
     * Insert Mail to Newsletter.
     *
     * @param SubscriberModel $subscriber
     * @return void
     */
    public function saveUserEmail(SubscriberModel $subscriber)
    {
        $userRow = $this->db()->select('email')
            ->from('users')
            ->where(['id' => $subscriber->getId()])
            ->execute()
            ->fetchRows();
        $userMail = $userRow[0]['email'];

        $newsletterMail = $this->countEmails($userMail);

        if ($newsletterMail == '0') {
            $this->db()->insert('newsletter_mails')
                ->values([
                    'email' => $userMail,
                    'selector' => $subscriber->getSelector(),
                    'confirmCode' => $subscriber->getConfirmCode(),
                    'doubleOptInDate' => $subscriber->getDoubleOptInDate(),
                    'doubleOptInConfirmed' => $subscriber->getDoubleOptInConfirmed(),
                ])
                ->execute();
        } else {
            $this->db()->delete('newsletter_mails')
                ->where(['email' => $userMail])
                ->execute();
        }
    }
}
