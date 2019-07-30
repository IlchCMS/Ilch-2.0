<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Bugtracker\Mappers;

use Modules\Bugtracker\Models\Comment as CommentModel;
use Modules\User\Mappers\User as UserMapper;
use Ilch\Date;

class Comment extends \Ilch\Mapper
{
    public function getAllCommentsByBugID($bugID)
    {
        $userMapper = new UserMapper();

        $link = $this->db()->getLink();
        $bugID = mysqli_real_escape_string($link, $bugID);


        $query = "SELECT * FROM [prefix]_bugtracker_comments WHERE bug_id = ?";
        $query = $this->db()->getSqlWithPrefix($query);
        $stmt = $link->prepare($query);
        $stmt->bind_param('i', $bugID);
        $stmt->execute();

        $res = $stmt->get_result();

        $i = 0;
        $comments = array();

        while($row = mysqli_fetch_assoc($res))
        {
            $id = $row['id'];
            $bugID = $row['bug_id'];
            $content = $row['content'];
            $user = $userMapper->getUserById((int) $row['poster_id']);
            $internOnly = (boolean) $row['intern_only'];
            $createTime = new Date($row['create_time']);

            $comments[$i] = new CommentModel($id, $bugID, $content, $user, $internOnly, $createTime);
            $i++;
        }

        return $comments;
    }

    public function getCommentByID($commentID)
    {
        $userMapper = new UserMapper();

        $link = $this->db()->getLink();
        $commentID = mysqli_real_escape_string($link, $commentID);


        $query = "SELECT * FROM [prefix]_bugtracker_comments WHERE id = ?";
        $query = $this->db()->getSqlWithPrefix($query);
        $stmt = $link->prepare($query);
        $stmt->bind_param('i', $commentID);
        $stmt->execute();

        $res = $stmt->get_result();

        $row = mysqli_fetch_assoc($res);

        $id = $row['id'];
        $bugID = $row['bug_id'];
        $content = $row['content'];
        $user = $userMapper->getUserById($row['poster_id']);
        $internOnly = (boolean) $row['intern_only'];
        $createTime = new Date($row['create_time']);

        return new CommentModel($id, $bugID, $content, $user, $internOnly, $createTime);
    }

    public function addComment($bugID, $content, $userID, $internOnly)
    {
        $link = $this->db()->getLink();

        $query = "INSERT INTO [prefix]_bugtracker_comments (`bug_id`, `content`, `poster_id`, `intern_only`) VALUES (?, ?, ?, ?)";
        $query = $this->db()->getSqlWithPrefix($query);
        $stmt = $link->prepare($query);
        $stmt->bind_param('isii', $bugID, $content, $userID, $internOnly);
        $stmt->execute();
    }

    public function saveComment($commentID, $content, $internOnly)
    {
        $link = $this->db()->getLink();

        $commentID = mysqli_real_escape_string($link, $commentID);
        $content = mysqli_real_escape_string($link, $content);
        $internOnly = mysqli_real_escape_string($link, $internOnly);
     
        $query = "UPDATE [prefix]_bugtracker_comments SET content = ?, intern_only = ? WHERE id = ?";
        $query = $this->db()->getSqlWithPrefix($query);
        $stmt = $link->prepare($query);
        $stmt->bind_param('sii', $content, $internOnly, $commentID);
        $stmt->execute();
    }

    public function deleteComment($commentID)
    {
        $link = $this->db()->getLink();

        $commentID = mysqli_real_escape_string($link, $commentID);

        $query = "DELETE FROM [prefix]_bugtracker_comments WHERE id = ?";
        $query = $this->db()->getSqlWithPrefix($query);
        $stmt = $link->prepare($query);
        $stmt->bind_param('i', $commentID);
        $stmt->execute();
    }
}
