<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Comment\Mappers;

use Modules\Comment\Models\Comment as CommentModel;
use Modules\Comment\Mappers\Comment as CommentMappers;

defined('ACCESS') or die('no direct access');

class Comment extends \Ilch\Mapper
{
    /**
     * @return CommentModel[]|null
     */
    public function getConfigByKey($key)
    {
        $commentsArray = $this->db()->select('*')
            ->from('cfg_comments')
            ->where(array('cc_bolactive' => 1))
            ->order(array('id' => 'DESC'))
            ->execute()
            ->fetchRows();

        $config = array();

        foreach ($commentsArray as $commentRow) {
            $cfgModel = new CommentModel();
            $cfgModel->setId($commentRow['id']);
            $cfgModel->setName($commentRow['cc_strname']);
            $cfgModel->setCode($commentRow['cc_strcode']);
            $cfgModel->setActive($commentRow['cc_bolactive']);
            $config[] = $cfgModel;
        }

        return $config;
    }
	


    /**
     * @param CommentModel $comment
     */
    public function save(CommentModel $comment)
    {
        $this->db()->insert('cfg_comments')
            ->values
            (
                array
                (
                    'cc_strname' => $comment->getName(),
                    'cc_strcode' => $comment->getCode(),
                    'cc_bolactive' => $comment->getActive(),
                )
            )
            ->execute();
    }

    /**
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('cfg_comments')
            ->where(array('id' => $id))
            ->execute();
    }
}
