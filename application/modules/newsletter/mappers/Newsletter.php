<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Newsletter\Mappers;

use Modules\Newsletter\Models\Newsletter as NewsletterModel;

class Newsletter extends \Ilch\Mapper
{
    /**
     * Gets the Newsletter entries.
     *
     * @param array $where
     * @return NewsletterModel[]|array
     */
    public function getEntries($where = [])
    {
        $entryArray = $this->db()->select('*')
                ->from('newsletter')
                ->where($where)
                ->order(['date_created' => 'DESC'])
                ->execute()
                ->fetchRows();

        if (empty($entryArray)) {
            return null;
        }

        $entry = [];

        foreach ($entryArray as $entries) {
            $entryModel = new NewsletterModel();
            $entryModel->setId($entries['id']);
            $entryModel->setUserId($entries['user_id']);
            $entryModel->setDateCreated($entries['date_created']);
            $entryModel->setSubject($entries['subject']);
            $entryModel->setText($entries['text']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Gets the Newsletter entries.
     *
     * @return NewsletterModel[]|array
     */
    public function getMail()
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
            $entryModel = new NewsletterModel();
            $entryModel->setEmail($entries['email']);
            $entryModel->setSelector($entries['selector']);
            $entryModel->setConfirmCode($entries['confirmCode']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Gets the Newsletter subscriber by the email.
     *
     * @param string $email
     * @return NewsletterModel|null
     */
    public function getSubscriberByEMail($email)
    {
        $entryArray = $this->db()->select('*')
            ->from('newsletter_mails')
            ->where(['email' => $email])
            ->execute()
            ->fetchAssoc();

        if (empty($entryArray)) {
            return null;
        }

        $entryModel = new NewsletterModel();
        $entryModel->setEmail($entryArray['email']);
        $entryModel->setSelector($entryArray['selector']);
        $entryModel->setConfirmCode($entryArray['confirmCode']);

        return $entryModel;
    }

    /**
     * Gets the Newsletter subscriber by the selector.
     *
     * @param string $selector
     * @return NewsletterModel|null
     */
    public function getSubscriberBySelector($selector)
    {
        $entryArray = $this->db()->select('*')
                ->from('newsletter_mails')
                ->where(['selector' => $selector])
                ->execute()
                ->fetchAssoc();

        if (empty($entryArray)) {
            return null;
        }

        $entryModel = new NewsletterModel();
        $entryModel->setEmail($entryArray['email']);
        $entryModel->setConfirmCode($entryArray['confirmCode']);

        return $entryModel;
    }

    /**
     * Get id of last added newletter (biggest id).
     *
     * @return integer
     */
    public function getLastId()
    {
        return $this->db()->select('MAX(id)')
                ->from('newsletter')
                ->execute()
                ->fetchCell();
    }

    /**
     * Gets newsletter.
     *
     * @param integer $id
     * @return NewsletterModel|null
     */
    public function getNewsletterById($id)
    {
        $newsletterRow = $this->db()->select('*')
                ->from('newsletter')
                ->where(['id' => $id])
                ->execute()
                ->fetchAssoc();

        if (empty($newsletterRow)) {
            return null;
        }

        $newsletterModel = new NewsletterModel();
        $newsletterModel->setId($newsletterRow['id']);
        $newsletterModel->setUserId($newsletterRow['user_id']);
        $newsletterModel->setDateCreated($newsletterRow['date_created']);
        $newsletterModel->setSubject($newsletterRow['subject']);
        $newsletterModel->setText($newsletterRow['text']);

        return $newsletterModel;
    }

    /**
     * Gets the Newsletter mail entries.
     *
     * @param string $email
     * @return integer
     */
    public function countEmails($email)
    {
        return $this->db()->select('COUNT(*)')
                ->from('newsletter_mails')
                ->where(['email' => $email])
                ->execute()
                ->fetchCell();
    }

    /**
     * Inserts newsletter model.
     *
     * @param NewsletterModel $newsletter
     */
    public function save(NewsletterModel $newsletter)
    {
        $this->db()->insert('newsletter')
                ->values([
                            'user_id' => $newsletter->getUserId(),
                            'date_created' => $newsletter->getDateCreated(),
                            'subject' => $newsletter->getSubject(),
                            'text' => $newsletter->getText(),
                        ])
                ->execute();
    }

    /**
     * Inserts newsletter mail model.
     *
     * @param NewsletterModel $newsletter
     */
    public function saveEmail(NewsletterModel $newsletter)
    {
        $this->db()->insert('newsletter_mails')
                ->values([
                            'email' => $newsletter->getEmail(),
                            'selector' => $newsletter->getSelector(),
                            'confirmCode' => $newsletter->getConfirmCode(),
                        ])
                ->execute();
    }

    /**
     * Deletes newsletter email with given email.
     *
     * @param string $email
     */
    public function deleteEmail($email)
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
    public function deleteSubscriberBySelector($selector)
    {
        $this->db()->delete('newsletter_mails')
                ->where(['selector' => $selector])
                ->execute();
    }

    /**
     * Deletes newsletter with given id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('newsletter')
                ->where(['id' => $id])
                ->execute();
    }
    
    /**
     * Gets the Newsletter entries.
     *
     * @param array $where
     * @return NewsletterModel[]|array
     */
    public function getSendMailUser()
    {
        return $this->db()->select()
                ->fields(['nm.email', 'nm.selector'])
                ->from(['nm' => 'newsletter_mails'])
                ->join(['u' => 'users'], 'u.email = nm.email', 'LEFT', ['name' => 'u.name'])
                ->execute()
                ->fetchRows();
    }

    /**
     * Insert Mail to Newsletter
     */
    public function saveUserEmail(NewsletterModel $newsletter)
    {
        $userRow = $this->db()->select('email')
                ->from('users')
                ->where(['id' => $newsletter->getId()])
                ->execute()
                ->fetchRows();
        $userMail = $userRow[0]['email'];
        
        $newsletterMail = $this->countEmails($userMail);

        if ($newsletterMail == '0') {
            $this->db()->insert('newsletter_mails')
                ->values([
                            'email' => $userMail,
                            'selector' => $newsletter->getSelector(),
                            'confirmCode' => $newsletter->getConfirmCode()
                        ])
                ->execute();
        } else {
            $this->db()->delete('newsletter_mails')
                ->where(['email' => $userMail])
                ->execute();
        }
    }
}
