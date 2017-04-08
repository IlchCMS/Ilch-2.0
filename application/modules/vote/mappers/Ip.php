<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Vote\Mappers;

use Modules\Vote\Models\Ip as IpModel;

class Ip extends \Ilch\Mapper
{
    public function getIP($pollId, $clientIP)
    {
        $ipRow = $this->db()->select('*')
            ->from('poll_ip')
            ->where(['poll_id' => $pollId, 'ip' => $clientIP])
            ->execute()
            ->fetchAssoc();

        if (empty($ipRow)) {
            return null;
        }

        $ipModel = new IpModel();
        $ipModel->setIP($ipRow['ip']);

        return $ipModel;
    }

    /**
     * Inserts Ip model.
     *
     * @param IpModel $vote
     */
    public function saveIP(IpModel $vote)
    {
        $fields = [
            'poll_id' => $vote->getPollId(),
            'ip' => $vote->getIP()
        ];

        $this->db()->insert('poll_ip')
            ->values($fields)
            ->execute();
    }
}
