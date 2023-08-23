<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Mappers;

use Ilch\Database\Exception;
use Ilch\Mapper;
use Modules\Forum\Models\ForumItem;
use Modules\Forum\Models\ForumPost as PostModel;
use Modules\User\Mappers\User as UserMapper;
use Modules\Forum\Mappers\Topic as TopicMapper;

class Forum extends Mapper
{
    /**
     * Get all forumItems by its parent (specified by its id)
     *
     * @param int $itemId
     * @param int|null $userId
     * @return ForumItem[]|[]
     * @throws Exception
     */
    public function getForumItemsByParent(int $itemId, int $userId = null): array
    {
        $items = [];
        $itemRows = $this->db()->select(['i.id', 'i.type', 'i.title', 'i.description', 'i.prefix'])
                ->from(['i' => 'forum_items'])
                ->join(['aa' => 'forum_accesses'], ['i.id = aa.item_id', 'aa.access_type' => 0], 'LEFT', ['read_access' => 'GROUP_CONCAT(DISTINCT aa.group_id)'])
                ->join(['ab' => 'forum_accesses'], ['i.id = ab.item_id', 'ab.access_type' => 1], 'LEFT', ['reply_access' => 'GROUP_CONCAT(DISTINCT ab.group_id)'])
                ->join(['ac' => 'forum_accesses'], ['i.id = ac.item_id', 'ac.access_type' => 2], 'LEFT', ['create_access' => 'GROUP_CONCAT(DISTINCT ac.group_id)'])
                ->where(['i.parent_id' => $itemId])
                ->group(['i.id'])
                ->order(['i.sort' => 'ASC'])
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
            $itemModel->setReadAccess($itemRow['read_access'] ?? '');
            $itemModel->setReplyAccess($itemRow['reply_access'] ?? '');
            $itemModel->setCreateAccess($itemRow['create_access'] ?? '');
            $itemModel->setSubItems($this->getForumItemsByParent($itemRow['id'], $userId));
            $itemModel->setTopics($this->getCountTopicsById($itemRow['id']));
            $itemModel->setLastPost($this->getLastPostByForumId($itemRow['id'], $userId));
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
    public function getForumById(int $id): ?ForumItem
    {
        $itemRows = $this->db()->select(['i.id', 'i.type', 'i.title', 'i.description', 'i.parent_id', 'i.prefix'])
                ->from(['i' => 'forum_items'])
                ->join(['aa' => 'forum_accesses'], ['i.id = aa.item_id', 'aa.access_type' => 0], 'LEFT', ['read_access' => 'GROUP_CONCAT(DISTINCT aa.group_id)'])
                ->join(['ab' => 'forum_accesses'], ['i.id = ab.item_id', 'ab.access_type' => 1], 'LEFT', ['reply_access' => 'GROUP_CONCAT(DISTINCT ab.group_id)'])
                ->join(['ac' => 'forum_accesses'], ['i.id = ac.item_id', 'ac.access_type' => 2], 'LEFT', ['create_access' => 'GROUP_CONCAT(DISTINCT ac.group_id)'])
                ->where(['i.id' => $id])
                ->order(['i.sort' => 'DESC'])
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
        $itemModel->setReadAccess($itemRows['read_access'] ?? '');
        $itemModel->setReplyAccess($itemRows['reply_access'] ?? '');
        $itemModel->setCreateAccess($itemRows['create_access'] ?? '');

        return $itemModel;
    }

    /**
     * Get forum by topic id.
     *
     * @param int $topicId
     * @return ForumItem|null
     */
    public function getForumByTopicId(int $topicId): ?ForumItem
    {
        $itemRow = $this->db()->select()
            ->fields(['t.id'])
            ->from(['t' => 'forum_topics'])
            ->join(['i' => 'forum_items'], 'i.id = t.forum_id', 'LEFT', ['i.id', 'i.type', 'i.title', 'i.description', 'i.prefix', 'i.parent_id'])
            ->join(['aa' => 'forum_accesses'], ['i.id = aa.item_id', 'aa.access_type' => 0], 'LEFT', ['read_access' => 'GROUP_CONCAT(DISTINCT aa.group_id)'])
            ->join(['ab' => 'forum_accesses'], ['i.id = ab.item_id', 'ab.access_type' => 1], 'LEFT', ['reply_access' => 'GROUP_CONCAT(DISTINCT ab.group_id)'])
            ->join(['ac' => 'forum_accesses'], ['i.id = ac.item_id', 'ac.access_type' => 2], 'LEFT', ['create_access' => 'GROUP_CONCAT(DISTINCT ac.group_id)'])
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
        $itemModel->setReadAccess($itemRow['read_access'] ?? '');
        $itemModel->setReplyAccess($itemRow['reply_access'] ?? '');
        $itemModel->setCreateAccess($itemRow['create_access'] ?? '');

        return $itemModel;
    }

    /**
     * @param int $forumId
     * @param int|null $userId
     * @return PostModel|null
     * @throws Exception
     */
    public function getLastPostByForumId(int $forumId, int $userId = null): ?PostModel
    {
        $select = $this->db()->select(['p.id', 'p.topic_id', 'p.user_id', 'p.date_created', 'p.forum_id'])
            ->from(['p' => 'forum_posts'])
            ->join(['t' => 'forum_topics'], 't.id = p.topic_id', 'LEFT', ['t.topic_title']);

        if ($userId) {
            $select->join(['tr' => 'forum_topics_read'], ['tr.user_id' => $userId, 'tr.topic_id = p.topic_id', 'tr.datetime >= p.date_created'], 'LEFT', ['topic_read' => 'tr.datetime'])
                ->join(['fr' => 'forum_read'], ['fr.user_id' => $userId, 'fr.forum_id = p.forum_id', 'fr.datetime >= p.date_created'], 'LEFT', ['forum_read' => 'fr.datetime']);
        }

        $lastPostRow = $select->where(['p.forum_id' => $forumId])
            ->order(['p.date_created' => 'DESC', 'p.id' => 'DESC'])
            ->execute()
            ->fetchAssoc();

        if (empty($lastPostRow)) {
            return null;
        }

        $postModel = new PostModel();
        $userMapper = new UserMapper();
        $postModel->setId($lastPostRow['id']);
        $user = $userMapper->getUserById($lastPostRow['user_id']);

        if ($user) {
            $postModel->setAutor($user);
        } else {
            $postModel->setAutor($userMapper->getDummyUser());
        }

        $postModel->setDateCreated($lastPostRow['date_created']);
        $postModel->setTopicId($lastPostRow['topic_id']);
        $postModel->setTopicTitle($lastPostRow['topic_title']);

        if ($userId) {
            $postModel->setRead($lastPostRow['topic_read'] || $lastPostRow['forum_read']);
        }

        return $postModel;
    }

    /**
     * Get category by parent id.
     *
     * @param int $id
     * @return ForumItem|null
     */
    public function getCatByParentId(int $id): ?ForumItem
    {
        $itemRows = $this->db()->select(['i.id', 'i.type', 'i.title', 'i.description', 'i.parent_id', 'i.prefix'])
                ->from(['i' => 'forum_items'])
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

        return $itemModel;
    }

    /**
     * @return array|null
     */
    public function getForumItems(): ?array
    {
        $items = [];
        $itemRows = $this->db()->select(['i.id', 'i.type', 'i.title', 'i.description', 'i.parent_id', 'i.prefix'])
            ->from(['i' => 'forum_items'])
            ->join(['aa' => 'forum_accesses'], ['i.id = aa.item_id', 'aa.access_type' => 0], 'LEFT', ['read_access' => 'GROUP_CONCAT(DISTINCT aa.group_id)'])
            ->join(['ab' => 'forum_accesses'], ['i.id = ab.item_id', 'ab.access_type' => 1], 'LEFT', ['reply_access' => 'GROUP_CONCAT(DISTINCT ab.group_id)'])
            ->join(['ac' => 'forum_accesses'], ['i.id = ac.item_id', 'ac.access_type' => 2], 'LEFT', ['create_access' => 'GROUP_CONCAT(DISTINCT ac.group_id)'])
            ->group(['i.id'])
            ->order(['i.sort' => 'ASC'])
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
            $itemModel->setReadAccess($itemRow['read_access'] ?? '');
            $itemModel->setReplyAccess($itemRow['reply_access'] ?? '');
            $itemModel->setCreateAccess($itemRow['create_access'] ?? '');
            $items[] = $itemModel;
        }

        return $items;
    }

    /**
     * @param int $id
     * @return int
     */
    public function getCountPostsById(int $id): int
    {
        return $this->db()->select(['p.id', 'p.topic_id', 't.id'])
            ->from(['t' => 'forum_topics'])
            ->join(['p' => 'forum_posts'], 'p.topic_id = t.id', 'LEFT', ['p.id', 'p.topic_id'])
            ->where(['t.forum_id' => $id])
            ->group(['t.id', 'p.id', 'p.topic_id'])
            ->execute()
            ->getFoundRows();
    }

    /**
     * Get the count of posts in a topic by the topic id.
     *
     * @param int $topicId
     * @return int
     */
    public function getCountPostsByTopicId(int $topicId): int
    {
        $countOfPosts = $this->db()->select('COUNT(id)')
            ->from('forum_posts')
            ->where(['topic_id' => $topicId])
            ->execute()
            ->fetchCell();

        if (empty($countOfPosts)) {
            return 0;
        }

        return $countOfPosts;
    }

    /**
     * Get count of topics by id.
     *
     * @param int $id
     * @return int
     */
    public function getCountTopicsById(int $id): int
    {
        $countOfTopics = $this->db()->select('COUNT(id)')
            ->from('forum_topics')
            ->where(['forum_id' => $id])
            ->execute()
            ->fetchCell();

        if (empty($countOfTopics)) {
            return 0;
        }

        return $countOfTopics;
    }

    /**
     * @return array|null
     */
    public function getForumPermas(): ?array
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

    /**
     * @param ForumItem $forumItem
     * @return int
     */
    public function saveItem(ForumItem $forumItem): int
    {
        $fields = [
            'title' => $forumItem->getTitle(),
            'sort' => $forumItem->getSort(),
            'parent_id' => $forumItem->getParentId(),
            'type' => $forumItem->getType(),
            'description' => $forumItem->getDesc(),
            'prefix' => $forumItem->getPrefix(),
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

        // Store access rights if the item is a forum and not a category.
        if ($forumItem->getType() == 1) {
            $this->db()->delete('forum_accesses')
                ->where(['item_id' => $itemId])
                ->execute();

            $access_rights = [
                explode(',', $forumItem->getReadAccess() ?? ''),
                explode(',', $forumItem->getReplyAccess() ?? ''),
                explode(',', $forumItem->getCreateAccess() ?? '')
            ];

            // 'read_access' => 0, 'reply_access' => 1, 'create_access' => 2
            $preparedRows = [];
            foreach ($access_rights as $type => $rights) {
                foreach($rights as $groupId) {
                    if ($groupId) {
                        $preparedRows[] = [$itemId, $groupId, $type];
                    }
                }
            }

            if (count($preparedRows)) {
                // Add access rights in chunks of 25 to the table. This prevents reaching the limit of 1000 rows
                // per insert, which would have been possible with a higher number of forums and user groups.
                $chunks = array_chunk($preparedRows, 25);
                foreach ($chunks as $chunk) {
                    $this->db()->insert('forum_accesses')
                        ->columns(['item_id', 'group_id', 'access_type'])
                        ->values($chunk)
                        ->execute();
                }
            }
        }

        return $itemId;
    }

    /**
     * @param int $id
     */
    public function deleteItem(int $id)
    {
        $topicMapper = new TopicMapper();
        $topics = $topicMapper->getTopicsListByForumId($id);
        foreach ($topics as $topicId) {
            $topicMapper->deleteById($topicId);
        }
        $this->db()->delete('forum_items')
            ->where(['id' => $id])
            ->execute();
    }
}
