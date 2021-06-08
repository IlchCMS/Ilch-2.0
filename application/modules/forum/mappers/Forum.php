<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Mappers;

use Modules\Forum\Models\ForumItem;
use Modules\Forum\Models\ForumPost as PostModel;
use Modules\User\Mappers\User as UserMapper;
use Modules\Forum\Mappers\Topic as TopicMapper;

class Forum extends \Ilch\Mapper
{
    /**
     * Get all forumItems by its parent (specified by its id)
     *
     * @param integer $itemId
     * @return ForumItem[]|[]
     * @throws \Ilch\Database\Exception
     */
    public function getForumItemsByParent($itemId)
    {
        $items = [];
        $itemRows = $this->db()->select('*')
                ->from('forum_items')
                ->where(['parent_id' => $itemId])
                ->order(['sort' => 'ASC'])
                ->execute()
                ->fetchRows();

        if (empty($itemRows)) {
            return [];
        }

        foreach ($itemRows as $itemRow) {
            $itemModel = new ForumItem();
            $itemModel->setId($itemRow['id']);
            $itemModel->setType($itemRow['type']);
            $itemModel->setTitle($itemRow['title']);
            $itemModel->setDesc($itemRow['description']);
            $itemModel->setParentId($itemId);
            $itemModel->setPrefix($itemRow['prefix']);
            $itemModel->setReadAccess($itemRow['read_access']);
            $itemModel->setReplayAccess($itemRow['replay_access']);
            $itemModel->setCreateAccess($itemRow['create_access']);
            $itemModel->setSubItems($this->getForumItemsByParent($itemRow['id']));
            $itemModel->setTopics($this->getCountTopicsById($itemRow['id']));
            $itemModel->setLastPost($this->getLastPostByTopicId($itemRow['id']));
            $itemModel->setPosts($this->getCountPostsById($itemRow['id']));
            $items[] = $itemModel;
        }

        return $items;
    }

    /**
     * Get forum by id.
     *
     * @param int $id
     * @return ForumItem|null
     */
    public function getForumById($id)
    {
        $itemRows = $this->db()->select('*')
                ->from('forum_items')
                ->where(['id' => $id])
                ->order(['sort' => 'DESC'])
                ->execute()
                ->fetchAssoc();

        if (empty($itemRows)) {
            return null;
        }

        $itemModel = new ForumItem();
        $itemModel->setId($itemRows['id']);
        $itemModel->setType($itemRows['type']);
        $itemModel->setTitle($itemRows['title']);
        $itemModel->setDesc($itemRows['description']);
        $itemModel->setParentId($itemRows['parent_id']);
        $itemModel->setPrefix($itemRows['prefix']);
        $itemModel->setReadAccess($itemRows['read_access']);
        $itemModel->setReplayAccess($itemRows['replay_access']);
        $itemModel->setCreateAccess($itemRows['create_access']);

        return $itemModel;
    }

    /**
     * Get forum by topic id.
     *
     * @param int $topicId
     * @return ForumItem|null
     */
    public function getForumByTopicId($topicId)
    {
        $itemRow = $this->db()->select()
            ->fields(['t.id', 't.topic_id'])
            ->from(['t' => 'forum_topics'])
            ->join(['i' => 'forum_items'], 'i.id = t.topic_id', 'LEFT', ['i.id', 'i.type', 'i.title', 'i.description', 'i.prefix', 'i.parent_id', 'i.read_access', 'i.replay_access', 'i.create_access'])
            ->where(['t.id' => $topicId])
            ->execute()
            ->fetchAssoc();

        if (empty($itemRow)) {
            return null;
        }

        $itemModel = new ForumItem();
        $itemModel->setId($itemRow['id']);
        $itemModel->setType($itemRow['type']);
        $itemModel->setTitle($itemRow['title']);
        $itemModel->setDesc($itemRow['description']);
        $itemModel->setParentId($itemRow['parent_id']);
        $itemModel->setPrefix($itemRow['prefix']);
        $itemModel->setReadAccess($itemRow['read_access']);
        $itemModel->setReplayAccess($itemRow['replay_access']);
        $itemModel->setCreateAccess($itemRow['create_access']);

        return $itemModel;
    }

    /**
     * @param int $topicId
     * @return PostModel|null
     * @throws \Ilch\Database\Exception
     */
    public function getLastPostByTopicId($topicId)
    {
        $sql = 'SELECT `t`.`id`, `t`.`topic_id`, `t`.`topic_title`, `p`.`read`, `p`.`id`, `p`.`topic_id`, `p`.`date_created`, `p`.`user_id`
                FROM `[prefix]_forum_topics` AS `t`
                LEFT JOIN `[prefix]_forum_posts` AS `p` ON `t`.`id` = `p`.`topic_id`
                WHERE `t`.`topic_id` = '.(int)$topicId.'
                ORDER BY `p`.`id` DESC';

        $fileRow = $this->db()->queryRow($sql);
        if (empty($fileRow)) {
            return null;
        }

        $entryModel = new PostModel();
        $userMapper = new UserMapper();
        $entryModel->setId($fileRow['id']);
        $user = $userMapper->getUserById($fileRow['user_id']);
        if ($user) {
            $entryModel->setAutor($user);
        } else {
            $entryModel->setAutor($userMapper->getDummyUser());
        }
        $entryModel->setDateCreated($fileRow['date_created']);
        $entryModel->setTopicId($fileRow['topic_id']);
        $entryModel->setTopicTitle($fileRow['topic_title']);
        $entryModel->setRead($fileRow['read']);

        return $entryModel;
    }

    /**
     * Get category by parent id.
     *
     * @param int $id
     * @return ForumItem|null
     */
    public function getCatByParentId($id)
    {
        $itemRows = $this->db()->select('*')
                ->from('forum_items')
                ->where(['id' => $id])
                ->order(['sort' => 'ASC'])
                ->execute()
                ->fetchAssoc();

        if (empty($itemRows)) {
            return null;
        }

        $itemModel = new ForumItem();
        $itemModel->setId($itemRows['id']);
        $itemModel->setType($itemRows['type']);
        $itemModel->setTitle($itemRows['title']);
        $itemModel->setDesc($itemRows['description']);
        $itemModel->setParentId($itemRows['parent_id']);
        $itemModel->setPrefix($itemRows['prefix']);
        $itemModel->setReadAccess($itemRows['read_access']);
        $itemModel->setReplayAccess($itemRows['replay_access']);
        $itemModel->setCreateAccess($itemRows['create_access']);

        return $itemModel;
    }

    /**
     * @param ForumItem $forumItem
     * @return int
     */
    public function saveItem(ForumItem $forumItem)
    {
        $fields = [
            'title' => $forumItem->getTitle(),
            'sort' => $forumItem->getSort(),
            'parent_id' => $forumItem->getParentId(),
            'type' => $forumItem->getType(),
            'description' => $forumItem->getDesc(),
            'prefix' => $forumItem->getPrefix(),
            'read_access' => $forumItem->getReadAccess(),
            'replay_access' => $forumItem->getReplayAccess(),
            'create_access' => $forumItem->getCreateAccess()
        ];

        foreach ($fields as $key => $value) {
            if ($value === null) {
                unset($fields[$key]);
            }
        }

        $itemId = (int)$this->db()->select('id')
            ->from('forum_items')
            ->where(['id' => $forumItem->getId()])
            ->execute()
            ->fetchCell();

        if ($itemId) {
            $this->db()->update('forum_items')
                ->values($fields)
                ->where(['id' => $itemId])
                ->execute();
        } else {
            $itemId = $this->db()->insert('forum_items')
                ->values($fields)
                ->execute();
        }

        return $itemId;
    }

    /**
     * @param $forumItem
     */
    public function deleteItem($forumItem)
    {
        $topicMapper = new TopicMapper();
        $id = $forumItem->getId();
        $topics = $topicMapper->getTopicsListByForumId($id);
        foreach ($topics as $topicId){
            $topicMapper->deleteById($topicId);
        }
        $this->db()->delete('forum_items')
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * @return array|null
     */
    public function getForumItems()
    {
        $items = [];
        $itemRows = $this->db()->select('*')
                ->from('forum_items')
                ->order(['sort' => 'ASC'])
                ->execute()
                ->fetchRows();

        if (empty($itemRows)) {
            return null;
        }

        foreach ($itemRows as $itemRow) {
            $itemModel = new ForumItem();
            $itemModel->setId($itemRow['id']);
            $itemModel->setType($itemRow['type']);
            $itemModel->setTitle($itemRow['title']);
            $itemModel->setDesc($itemRow['description']);
            $itemModel->setParentId($itemRow['parent_id']);
            $itemModel->setPrefix($itemRow['prefix']);
            $itemModel->setReadAccess($itemRow['read_access']);
            $itemModel->setReplayAccess($itemRow['replay_access']);
            $itemModel->setCreateAccess($itemRow['create_access']);
            $items[] = $itemModel;
        }

        return $items;
    }

    /**
     * @param int $id
     * @return int
     */
    public function getCountPostsById($id)
    {
        return $this->db()->select('*')
            ->fields(['p.id', 'p.topic_id', 't.id', 't.topic_id'])
            ->from(['t' => 'forum_topics'])
            ->join(['p' => 'forum_posts'], 'p.topic_id = t.id', 'LEFT', ['p.id', 'p.topic_id'])
            ->where(['t.topic_id' => $id])
            ->group(['t.id', 't.topic_id', 'p.id', 'p.topic_id'])
            ->execute()
            ->getFoundRows();
    }

    /**
     * Get the count of posts in a topic by the topic id.
     *
     * @param int $topicId
     * @return int|string
     * @throws \Ilch\Database\Exception
     */
    public function getCountPostsByTopicId($topicId)
    {
        $countOfPosts = $this->db()->select('COUNT(id)')
            ->from('forum_posts')
            ->where(['topic_id' => (int)$topicId])
            ->execute()
            ->fetchCell();

        if (empty($countOfPosts)) {
            return '0';
        }

        return $countOfPosts;
    }

    /**
     * Get count of topics by id.
     *
     * @param int $id
     * @return int|string
     * @throws \Ilch\Database\Exception
     */
    public function getCountTopicsById($id)
    {
        $countOfTopics = $this->db()->select('COUNT(topic_id)')
            ->from('forum_topics')
            ->where(['topic_id' => (int)$id])
            ->execute()
            ->fetchCell();

        if (empty($countOfTopics)) {
            return '0';
        }

        return $countOfTopics;
    }

    /**
     * @return array|null
     * @throws \Ilch\Database\Exception
     */
    public function getForumPermas()
    {
        $permas = $this->db()->select('*')
            ->from('forum_items')
            ->execute()
            ->fetchRows();

        $permaArray = [];

        if (empty($permas)) {
            return null;
        }

        foreach ($permas as $perma) {
            $permaArray[$perma['title']] = $perma;
        }

        return $permaArray;
    }
}
