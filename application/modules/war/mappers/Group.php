<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Mappers;

use Modules\War\Models\Group as GroupModel;

class Group extends \Ilch\Mapper
{
    /**
     * Gets the Groups.
     *
     * @param array $where
     * @param \Ilch\Pagination|null $pagination
     * @return GroupModel[]|array
     */
    public function getGroups($where = array(), $pagination = null)
    {
        $select = $this->db()->select('*')
            ->from('war_groups')
            ->where($where)
            ->order(array('id' => 'DESC'));

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $entryArray = $result->fetchRows();

        if (empty($entryArray)) {
            return null;
        }

        $entry = array();

        foreach ($entryArray as $entries) {
            $entryModel = new GroupModel();
            $entryModel->setId($entries['id']);
            $entryModel->setGroupName($entries['name']);
            $entryModel->setGroupTag($entries['tag']);
            $entryModel->setGroupImage($entries['image']);
            $entryModel->setGroupMember($entries['member']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Gets the Group List
     *
     * @param \Ilch\Pagination|null $pagination
     * @return GroupModel[]|array
     */
    public function getGroupList($pagination = null)
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS g.id, g.name, g.tag, g.image, g.member, m.url, m.url_thumb
                FROM `[prefix]_war_groups` as g
                LEFT JOIN [prefix]_media m ON g.image = m.url
                ORDER by g.id DESC
                LIMIT '.implode(',',$pagination->getLimit());

        $groupArray = $this->db()->queryArray($sql);
        $pagination->setRows($this->db()->querycell('SELECT FOUND_ROWS()'));

        if (empty($groupArray)) {
            return null;
        }

        $entry = array();

        foreach ($groupArray as $entries) {
            $entryModel = new GroupModel();
            $entryModel->setId($entries['id']);
            $entryModel->setGroupName($entries['name']);
            $entryModel->setGroupTag($entries['tag']);
            $entryModel->setGroupImage($entries['url_thumb']);
            $entryModel->setGroupMember($entries['member']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Gets group by id.
     *
     * @param integer $id
     * @return GroupModel|null
     */
    public function getGroupById($id)
    {
        $groupRow = $this->db()->select('*')
            ->from('war_groups')
            ->where(array('id' => $id))
            ->execute()
            ->fetchAssoc();

        if (empty($groupRow)) {
            return null;
        }

        $groupModel = new GroupModel();
        $groupModel->setId($groupRow['id']);
        $groupModel->setGroupName($groupRow['name']);
        $groupModel->setGroupTag($groupRow['tag']);
        $groupModel->setGroupImage($groupRow['image']);
        $groupModel->setGroupMember($groupRow['member']);

        return $groupModel;
    }

    /**
     * Inserts or updates group entry.
     *
     * @param GroupModel $model
     */
    public function save(GroupModel $model)
    {
        $fields = array
        (
            'name' => $model->getGroupName(),
            'tag' => $model->getGroupTag(),
            'image' => $model->getGroupImage(),
            'member' => $model->getGroupMember(),
        );

        if ($model->getId()) {
            $this->db()->update('war_groups')
                ->values($fields)
                ->where(array('id' => $model->getId()))
                ->execute();
        } else {
            $this->db()->insert('war_groups')
                ->values($fields)
                ->execute();
        }
    }

    public function delete($id)
    {
        $this->db()->delete('war_groups')
            ->where(array('id' => $id))
            ->execute();
    }
}
