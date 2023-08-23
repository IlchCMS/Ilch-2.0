<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Mappers;

use Ilch\Mapper;
use Modules\Forum\Models\Rank as RankModel;

class Rank extends Mapper
{
    /**
     * Get all ranks.
     *
     * @return RankModel[]|array
     */
    public function getRanks(): array
    {
        $ranksArray = $this->db()->select('*')
            ->from('forum_ranks')
            ->order(['posts' => 'ASC'])
            ->execute()
            ->fetchRows();

        $ranks = [];

        foreach ($ranksArray as $rankRow) {
            $rankModel = new RankModel();
            $rankModel->setId($rankRow['id']);
            $rankModel->setTitle($rankRow['title']);
            $rankModel->setPosts($rankRow['posts']);
            $ranks[] = $rankModel;
        }

        return $ranks;
    }

    /**
     * Get rank by id.
     *
     * @param int $id
     * @return RankModel|null
     */
    public function getRankById(int $id): ?RankModel
    {
        $rank = $this->db()->select('*')
            ->from('forum_ranks')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (empty($rank)) {
            return null;
        }

        $rankModel = new RankModel();

        $rankModel->setId($rank['id']);
        $rankModel->setTitle($rank['title']);
        $rankModel->setPosts($rank['posts']);

        return $rankModel;
    }

    /**
     * Get rank by posts.
     *
     * @param int $posts
     * @return RankModel|null
     */
    public function getRankByPosts(int $posts): ?RankModel
    {
        $rank = $this->db()->select('*')
            ->from('forum_ranks')
            ->where(['posts <=' => $posts])
            ->order(['posts' => 'DESC'])
            ->execute()
            ->fetchAssoc();

        if (empty($rank)) {
            return null;
        }

        $rankModel = new RankModel();

        $rankModel->setId($rank['id']);
        $rankModel->setTitle($rank['title']);
        $rankModel->setPosts($rank['posts']);

        return $rankModel;
    }

    /**
     * Inserts or updates rank model.
     *
     * @param RankModel $rank
     */
    public function save(RankModel $rank)
    {
        if ($rank->getId()) {
            $this->db()->update('forum_ranks')
                ->values(['title' => $rank->getTitle(), 'posts' => $rank->getPosts()])
                ->where(['id' => $rank->getId()])
                ->execute();
        } else {
            $this->db()->insert('forum_ranks')
                ->values(['title' => $rank->getTitle(), 'posts' => $rank->getPosts()])
                ->execute();
        }
    }

    /**
     * Deletes rank with given id.
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->db()->delete('forum_ranks')
            ->where(['id' => $id])
            ->execute();
    }
}
