<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Newsletter\Mappers;

use Ilch\Mapper;
use Modules\Newsletter\Models\Newsletter as NewsletterModel;

class Newsletter extends Mapper
{
    /**
     * Gets the Newsletter entries.
     *
     * @param array $where
     * @return NewsletterModel[]|array
     */
    public function getEntries(array $where = []): ?array
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
     * Get id of last added newletter (biggest id).
     *
     * @return int
     */
    public function getLastId(): int
    {
        return $this->db()->select('MAX(id)')
                ->from('newsletter')
                ->execute()
                ->fetchCell();
    }

    /**
     * Gets newsletter.
     *
     * @param int $id
     * @return NewsletterModel|null
     */
    public function getNewsletterById(int $id): ?NewsletterModel
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
     * Deletes newsletter with given id.
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->db()->delete('newsletter')
                ->where(['id' => $id])
                ->execute();
    }
}
