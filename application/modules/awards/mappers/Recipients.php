<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Awards\Mappers;

use http\Exception\InvalidArgumentException;
use Ilch\Mapper;
use Modules\Awards\Models\Recipient as RecipientModel;

/**
 * The recipient mapper.
 */
class Recipients extends Mapper
{
    /**
     * Get recipients.
     *
     * @param array $where
     * @return array
     */
    public function getRecipients(array $where = []): array
    {
        $recipientsArray = $this->db()->select('*')
            ->from('awards_recipients')
            ->where($where)
            ->execute()
            ->fetchRows();

        if (empty($recipientsArray)) {
            return [];
        }

        $recipients = [];
        foreach ($recipientsArray as $recipient) {
            $recipientModel = new RecipientModel();
            $recipientModel->setAwardId($recipient['id'])
                ->setUtId($recipient['ut_id'])
                ->setTyp($recipient['typ']);
            $recipients[] = $recipientModel;
        }

        return $recipients;
    }

    /**
     * Saves a single or multiple recipients of an award at once.
     * Don't supply more than 1000 at a time.
     *
     * @param RecipientModel[] $recipients
     * @return int number of affected rows.
     */
    public function saveMulti(array $recipients): int
    {
        if (count($recipients) > 1000) {
            throw new InvalidArgumentException('Too many recipients. There is a limit of 1000.');
        }

        $fields = [];
        foreach($recipients as $recipient) {
            $fields[] = [$recipient->getAwardId(), $recipient->getUtId(), $recipient->getTyp()];
        }

        $this->db()->delete()->from('awards_recipients')
            ->where(['award_id' => $recipients[0]->getAwardId()])
            ->execute();

        return $this->db()->insert('awards_recipients')
            ->columns(['award_id', 'ut_id', 'typ'])
            ->values($fields)
            ->execute();
    }
}
