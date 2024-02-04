<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Newsletter\Mappers;

use Ilch\Database\Mysql\Result;
use Ilch\Mapper;
use Modules\Newsletter\Models\Subscriber as SubscriberModel;

class Subscriber extends Mapper
{
    /**
     * Gets all subscribers.
     *
     * @return SubscriberModel[]|array
     */
    public function getSubscribers(): ?array
    {
        $subscribersArray = $this->db()->select('*')
                ->from('newsletter_mails')
                ->execute()
                ->fetchRows();

        if (empty($subscribersArray)) {
            return null;
        }

        $subscribers = [];

        foreach ($subscribersArray as $subscriber) {
            $subscriberModel = new SubscriberModel();
            $subscriberModel->setId($subscriber['id']);
            $subscriberModel->setEmail($subscriber['email']);
            $subscriberModel->setSelector($subscriber['selector']);
            $subscriberModel->setConfirmCode($subscriber['confirmCode']);
            $subscriberModel->setDoubleOptInDate($subscriber['doubleOptInDate']);
            $subscriberModel->setDoubleOptInConfirmed($subscriber['doubleOptInConfirmed']);
            $subscribers[] = $subscriberModel;
        }

        return $subscribers;
    }

    /**
     * Gets the subscriber by the email.
     *
     * @param string $email
     * @return SubscriberModel|null
     */
    public function getSubscriberByEMail(string $email): ?SubscriberModel
    {
        return $this->getSubscriberBy(['email' => $email]);
    }

    /**
     * Gets subscriber by the selector.
     *
     * @param string $selector
     * @return SubscriberModel|null
     */
    public function getSubscriberBySelector(string $selector): ?SubscriberModel
    {
        return $this->getSubscriberBy(['selector' => $selector]);
    }

    /**
     * Gets subscriber by supplied condition.
     *
     * @param array $where
     * @return SubscriberModel|null
     */
    private function getSubscriberBy(array $where): ?SubscriberModel
    {
        $subscriberArray = $this->db()->select('*')
            ->from('newsletter_mails')
            ->where($where)
            ->execute()
            ->fetchAssoc();

        if (empty($subscriberArray)) {
            return null;
        }

        $subscriberModel = new SubscriberModel();
        $subscriberModel->setId($subscriberArray['id']);
        $subscriberModel->setEmail($subscriberArray['email']);
        $subscriberModel->setSelector($subscriberArray['selector']);
        $subscriberModel->setConfirmCode($subscriberArray['confirmCode']);
        $subscriberModel->setDoubleOptInDate($subscriberArray['doubleOptInDate']);
        $subscriberModel->setDoubleOptInConfirmed($subscriberArray['doubleOptInConfirmed']);

        return $subscriberModel;
    }

    /**
     * Gets the Newsletter entries.
     *
     * @return array
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
     * Save or delete user as subscriber.
     *
     * @param SubscriberModel $subscriber
     * @return int|Result
     */
    public function saveUserAsSubscriber(SubscriberModel $subscriber)
    {
        $userRow = $this->db()->select('email')
            ->from('users')
            ->where(['id' => $subscriber->getId()])
            ->execute()
            ->fetchRows();
        $userMail = $userRow[0]['email'];

        $newsletterMail = $this->countEmails($userMail);

        if ($newsletterMail == '0') {
            return $this->db()->insert('newsletter_mails')
                ->values([
                    'email' => $userMail,
                    'selector' => $subscriber->getSelector(),
                    'confirmCode' => $subscriber->getConfirmCode(),
                    'doubleOptInDate' => $subscriber->getDoubleOptInDate(),
                    'doubleOptInConfirmed' => $subscriber->getDoubleOptInConfirmed(),
                ])
                ->execute();
        } else {
            return $this->db()->delete('newsletter_mails')
                ->where(['email' => $userMail])
                ->execute();
        }
    }

    /**
     * Inserts or updates subscriber model.
     *
     * @param SubscriberModel $subscriber
     * @return int
     */
    public function saveSubscriber(SubscriberModel $subscriber): int
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
     * Deletes subscriber with given email.
     *
     * @param string $email
     */
    public function deleteSubscriberByEmail(string $email)
    {
        $this->db()->delete('newsletter_mails')
                ->where(['email' => $email])
                ->execute();
    }

    /**
     * Deletes subscriber with given selector.
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
}
