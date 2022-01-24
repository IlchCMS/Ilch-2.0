<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Mappers;

use Modules\User\Models\Group as GroupModel;
use Modules\Forum\Models\GroupRank as GroupRankModel;

class GroupRanking extends \Ilch\Mapper
{
    /**
     * Get the group ranking.
     *
     * @param array $where
     * @return GroupRankModel[]|[]
     */
    public function getGroupRanking($where = [])
    {
        $items = [];
        $itemRows = $this->db()->select('*')
            ->from('forum_groupranking')
            ->order(['rank' => 'ASC'])
            ->where($where)
            ->execute()
            ->fetchRows();

        if (empty($itemRows)) {
            return [];
        }

        foreach ($itemRows as $itemRow) {
            $itemModel = new GroupRankModel();
            $itemModel->setId($itemRow['id']);
            $itemModel->setGroupId($itemRow['group_id']);
            $itemModel->setRank($itemRow['rank']);
            $items[] = $itemModel;
        }

        return $items;
    }

    /**
     * Get GroupRanking by group id.
     *
     * @param $group_id
     * @return mixed
     */
    public function getGroupRankingByGroupId($group_id)
    {
        $groupRanking = $this->getGroupRanking(['group_id' => $group_id]);

        return reset($groupRanking);
    }

    /**
     * Return the highest ranked group of an array of groups.
     *
     * @param [] $group_ids
     * @return GroupRankModel|null
     */
    public function getHighestRankOfGroups($group_ids)
    {
        $select = $this->db()->select('*')
            ->from('forum_groupranking');

        foreach ($group_ids as $groupId) {
            $select->orWhere(['group_id' => $groupId]);
        }

        $itemRow = $select->order(['rank' => 'ASC'])
            ->limit(1)
            ->execute()
            ->fetchAssoc();

        if (empty($itemRow)) {
            return null;
        }

        $itemModel = new GroupRankModel();
        $itemModel->setId($itemRow['id']);
        $itemModel->setGroupId($itemRow['group_id']);
        $itemModel->setRank($itemRow['rank']);

        return $itemModel;
    }

    /**
     * Return the user groups from the user module sorted by the rank
     * of the groups set in the forum module.
     *
     * @return array|GroupModel[]
     */
    public function getUserGroupsSortedByRank()
    {
        $groupRows = $this->db()->select('*')
            ->from(['r' => 'forum_groupranking'])
            ->join(['g' => 'groups'], 'g.id = r.group_id', 'RIGHT')
            ->order(['r.rank' => 'ASC'])
            ->execute()
            ->fetchRows();

        if (empty($groupRows)) {
            return [];
        }

        $groups = [];
        foreach ($groupRows as $groupRow) {
            $groupModel = new GroupModel();
            $groupModel->setId($groupRow['id']);
            $groupModel->setName($groupRow['name']);
            $groups[] = $groupModel;
        }

        return $groups;
    }

    /**
     * Save group rankings.
     *
     * @param $groups
     */
    public function saveGroupRanking($groups)
    {
        foreach ($groups as $rank => $group_id) {
            $groupRanking = $this->getGroupRankingByGroupId($group_id);

            if (empty($groupRanking)) {
                // New entry
                $this->db()->insert('forum_groupranking')
                    ->values(['rank' => $rank, 'group_id' => $group_id])
                    ->execute();
            } else {
                // Existing entry
                $this->db()->update('forum_groupranking')
                    ->values(['rank' => $rank])
                    ->where(['group_id' => $group_id])
                    ->execute();
            }
        }
    }
}
