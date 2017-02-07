<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Mappers;

use Modules\War\Models\Accept as AcceptModel;

class Accept extends \Ilch\Mapper
{
    /**
     * Gets the accept
     *
     * @param array $where
     * @return null|AcceptModel[]
     */
    public function getAcceptByWhere($where = [])
    {
        $select = $this->db()->select('*')
            ->from('war_accept')
            ->where($where)
            ->order(['id' => 'DESC'])
            ->execute()
            ->fetchRows();

        if (empty($select)) {
            return null;
        }

        $accepts = [];

        foreach ($select as $accept) {
            $acceptModel = new AcceptModel();
            $acceptModel->setId($accept['id']);
            $acceptModel->setWarId($accept['war_id']);
            $acceptModel->setUserId($accept['user_id']);
            $acceptModel->setAccept($accept['accept']);
            $accepts[] = $acceptModel;
        }

        return $accepts;
    }

    /**
     * Gets the accept by groupId and warId
     *
     * @param string $groupId, $warId
     * @return null|AcceptModel[]
     */
    public function getAcceptListByGroupId($groupId, $warId)
    {
        $select = $this->db()->select('*')
            ->fields(['u.group_id', 'u.user_id', 'a.war_id'])
            ->from(['u' => 'users_groups'])
            ->join(['a' => 'war_accept'], 'a.user_id = u.user_id', 'LEFT', ['a.id', 'a.user_id', 'a.accept', 'a.war_id'])
            ->where(['u.group_id' => $groupId, 'a.war_id' => $warId])
            ->execute()
            ->fetchRows();

        if (empty($select)) {
            return null;
        }

        $accepts = [];

        foreach ($select as $accept) {
            $acceptModel = new AcceptModel();
            $acceptModel->setId($accept['id']);
            $acceptModel->setWarId($accept['war_id']);
            $acceptModel->setUserId($accept['user_id']);
            $acceptModel->setAccept($accept['accept']);
            $accepts[] = $acceptModel;
        }

        return $accepts;
    }

    /**
     * Inserts or updates accept entry.
     *
     * @param AcceptModel $model
     */
    public function save(AcceptModel $model)
    {
        $fields = [
            'war_id' => $model->getWarId(),
            'user_id' => $model->getUserId(),
            'accept' => $model->getAccept()
        ];

        if ($model->getId()) {
            $this->db()->update('war_accept')
                ->values($fields)
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            $this->db()->insert('war_accept')
                ->values($fields)
                ->execute();
        }
    }
}
