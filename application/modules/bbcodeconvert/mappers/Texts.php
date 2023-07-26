<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Bbcodeconvert\Mappers;

use Ilch\Database\Mysql\Result;
use Ilch\Mapper;

/**
 * Get and update the texts. This mapper is used for the following modules: contact, events, forum, guestbook, jobs and teams.
 */
class Texts extends Mapper
{
    public $table = '';

    /**
     * Get the texts.
     *
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getTexts(int $offset, int $limit): array
    {
        $texts = $this->db()->select(['id', 'text'])
            ->from($this->table)
            ->limit([$offset, $limit])
            ->execute()
            ->fetchRows('id');

        if (empty($texts)) {
            return [];
        }

        return $texts;
    }

    /**
     * Returns the count of texts.
     *
     * @return int
     */
    public function getCount(): int
    {
        return (int)$this->db()->select('COUNT(*)')
            ->from($this->table)
            ->execute()
            ->fetchCell();
    }

    /**
     * Update the text.
     *
     * @param int $id
     * @param string $text
     * @return Result|int
     */
    public function updateText(int $id, string $text): int
    {
        return $this->db()->update()->table($this->table)
            ->values(['text' => $text])
            ->where(['id' => $id])
            ->execute();
    }
}
