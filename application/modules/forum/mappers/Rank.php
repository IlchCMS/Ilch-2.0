<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Mappers;

use Modules\Forum\Models\Rank as RankModel;

class Rank extends \Ilch\Mapper
{
    /**
     * Get all ranks.
     *
     * @return RankModel[]|array
     */
    public function getRanks()
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
     * @param integer $id
     * @return RankModel
     */
    public function getRankById($id)
    {
        $fileRow = $this->db()->select('*')
            ->from('forum_ranks')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        $rankModel = new RankModel();

        $rankModel->setId($fileRow['id']);
        $rankModel->setTitle($fileRow['title']);
        $rankModel->setPosts($fileRow['posts']);

        return $rankModel;
    }

    /**
     * Get rank by posts.
     *
     * @param integer $posts
     * @return RankModel
     */
    public function getRankByPosts($posts)
    {
        $fileRow = $this->db()->select('*')
            ->from('forum_ranks')
            ->where(['posts <=' => $posts])
            ->order(['posts' => 'DESC'])
            ->execute()
            ->fetchAssoc();

        $rankModel = new RankModel();

        $rankModel->setId($fileRow['id']);
        $rankModel->setTitle($fileRow['title']);
        $rankModel->setPosts($fileRow['posts']);

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
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('forum_ranks')
            ->where(['id' => $id])
            ->execute();
    }
}
