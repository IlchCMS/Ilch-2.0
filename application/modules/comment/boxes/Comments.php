<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Comment\Boxes;

use Modules\Comment\Mappers\Comment as CommentMapper;

class Comments extends \Ilch\Box
{
    public function render()
    {
        $commentMapper = new CommentMapper();

        $this->getView()->set('comments', $commentMapper->getComments($this->getConfig()->get('comment_box_comments_limit')));
    }
}
