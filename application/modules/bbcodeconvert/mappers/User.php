<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Bbcodeconvert\Mappers;

use Ilch\Database\Mysql\Result;
use Ilch\Mapper;

/**
 * Get and update the signatures and replies in the database.
 */
class User extends Mapper
{
    /**
     * Get the signatures.
     *
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getSignatures(int $offset, int $limit): array
    {
        $signatures = $this->db()->select(['id', 'signature'])
            ->from('users')
            ->limit([$offset, $limit])
            ->execute()
            ->fetchRows('id');

        if (empty($signatures)) {
            return [];
        }

        return $signatures;
    }

    /**
     * Get the replies.
     *
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getReplies(int $offset, int $limit): array
    {
        $replies = $this->db()->select(['id', 'reply'])
            ->from('users_dialog_reply')
            ->limit([$offset, $limit])
            ->execute()
            ->fetchRows('id');

        if (empty($replies)) {
            return [];
        }

        return $replies;
    }

    /**
     * Returns the count of all items (signatures and dialog replys) to convert.
     *
     * @return int
     */
    public function getCount(): int
    {
        $count = (int)$this->db()->select('COUNT(*)')
            ->from('users')
            ->execute()
            ->fetchCell();

        $count += (int)$this->db()->select('COUNT(*)')
            ->from('users_dialog_reply')
            ->execute()
            ->fetchCell();

        return $count;
    }

    /**
     * Update the signature.
     *
     * @param int $id
     * @param string $signature
     * @return Result|int
     */
    public function updateSignature(int $id, string $signature): int
    {
        return $this->db()->update()->table('users')
            ->values(['signature' => $signature])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Update the reply.
     *
     * @param int $id
     * @param string $reply
     * @return int
     */
    public function updateReply(int $id, string $reply): int
    {
        return $this->db()->update()->table('users_dialog_reply')
            ->values(['reply' => $reply])
            ->where(['id' => $id])
            ->execute();
    }
}
