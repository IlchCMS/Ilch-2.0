<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Bugtracker\Mappers;

use Modules\Bugtracker\Models\Bug as BugModel;
use Modules\Bugtracker\Models\Status as StatusModel;
use Modules\Bugtracker\Models\SubCategory as SubCategoryModel;
use Modules\Bugtracker\Models\Category as CategoryModel;

use Modules\User\Mappers\User as UserMapper;
use Modules\Bugtracker\Mappers\Comment as CommentMapper;
use Modules\Bugtracker\Mappers\Attachment as AttachmentMapper;
use Ilch\Date;

class Bug extends \Ilch\Mapper
{
    public function getAllBugs()
    {
        $userMapper = new UserMapper();
        $commentMapper = new CommentMapper();


        $query = "SELECT [prefix]_bugs.`id`, `sub_category_id`, [prefix]_bugtracker_sub_categories.name AS sub_category_name, [prefix]_bugtracker_sub_categories.category_id,
	                  [prefix]_bugtracker_categories.name AS category_name, `title`, `description`, `priority`, `creator_id`, `progress`,
                      `status_id`, [prefix]_bugtracker_status.name AS status_name, [prefix]_bugtracker_status.css_class AS status_css_class, `intern_only`, `update_time`, `create_time`
                  FROM [prefix]_bugs
                  JOIN [prefix]_bugtracker_status
	                  ON [prefix]_bugs.status_id = [prefix]_bugtracker_status.id
                  JOIN [prefix]_bugtracker_sub_categories
	                  ON [prefix]_bugs.sub_category_id = [prefix]_bugtracker_sub_categories.id
                  JOIN [prefix]_bugtracker_categories
                  ON [prefix]_bugtracker_sub_categories.category_id = [prefix]_bugtracker_categories.id";
        $res = $this->db()->query($query);

        $i = 0;
        $bugs = array();

        while($row = mysqli_fetch_assoc($res))
        {
            $bugID = $row['id'];
            $subCategory = new SubCategoryModel($row['sub_category_id'], new CategoryModel($row['category_id'], $row['category_name']), $row['sub_category_name']);
            $priority = $row['priority'];
            $title = $row['title'];
            $description = $row['description'];
            $user = $userMapper->getUserById($row['creator_id']);
            $assignedUsers = $this->getAssignedUsers($bugID);
            $progress = $row['progress'];
            $status = new StatusModel($row['status_id'], $row['status_name'], $row['status_css_class']);
            $likes = $this->getLikes($bugID);
            $dislikes = $this->getDislikes($bugID);
            $internOnly = $row['intern_only'];
            $updateTime = new Date($row['update_time']);
            $createTime = new Date($row['create_time']);

            $comments = $commentMapper->getAllCommentsByBugID($bugID);

            $bugs[$i] = new BugModel($bugID, $subCategory, $priority, $title, $description, $user, $assignedUsers, $progress, $status, $likes, $dislikes, $internOnly, $updateTime, $createTime, $comments);
            $i++;
        }

        return $bugs;
    }

    public function getBugByID($bugID)
    {
        $userMapper = new UserMapper();
        $commentMapper = new CommentMapper();

        $link = $this->db()->getLink();


        $query = "SELECT [prefix]_bugs.`id`, `sub_category_id`, [prefix]_bugtracker_sub_categories.name AS sub_category_name, [prefix]_bugtracker_sub_categories.category_id,
	                  [prefix]_bugtracker_categories.name AS category_name, `title`, `description`, `priority`, `creator_id`,
	                  `progress`, `status_id`, [prefix]_bugtracker_status.name AS status_name, [prefix]_bugtracker_status.css_class AS status_css_class, `intern_only`, `update_time`, `create_time`
                  FROM [prefix]_bugs
                  JOIN [prefix]_bugtracker_status
	                  ON [prefix]_bugs.status_id = [prefix]_bugtracker_status.id
                  JOIN [prefix]_bugtracker_sub_categories
	                  ON [prefix]_bugs.sub_category_id = [prefix]_bugtracker_sub_categories.id
                  JOIN [prefix]_bugtracker_categories
                  ON [prefix]_bugtracker_sub_categories.category_id = [prefix]_bugtracker_categories.id
                  WHERE [prefix]_bugs.id = ?";
        $query = $this->db()->getSqlWithPrefix($query);
        $stmt = $link->prepare($query);
        $stmt->bind_param('i', $bugID);
        $stmt->execute();

        $res = $stmt->get_result();

        if($res->num_rows < 1)
        {
            return null;
        }

        $row = mysqli_fetch_assoc($res);

        $bugID = $row['id'];
        $subCategory = new SubCategoryModel($row['sub_category_id'], new CategoryModel($row['category_id'], $row['category_name']), $row['sub_category_name']);
        $priority = $row['priority'];
        $title = $row['title'];
        $description = $row['description'];
        $user = $userMapper->getUserById($row['creator_id']);
        $assignedUsers = $this->getAssignedUsers($bugID);
        $progress = $row['progress'];
        $status = new StatusModel($row['status_id'], $row['status_name'], $row['status_css_class']);
        $likes = $this->getLikes($bugID);
        $dislikes = $this->getDislikes($bugID);
        $internOnly = $row['intern_only'];
        $updateTime = new Date($row['update_time']);
        $createTime = new Date($row['create_time']);

        $comments = $commentMapper->getAllCommentsByBugID($bugID);

        return new BugModel($bugID, $subCategory, $priority, $title, $description, $user, $assignedUsers, $progress, $status, $likes, $dislikes, $internOnly, $updateTime, $createTime, $comments);
    }

    public function getAllBugsByFilter($keywords, $status, $category, $sub_category, $my_reports, $filter_internal_reports_only)
    {
        $userMapper = new UserMapper();
        $commentMapper = new CommentMapper();

        $link = $this->db()->getLink();

        $keywords = mysqli_real_escape_string($link, $keywords);
        $status = mysqli_real_escape_string($link, $status);
        $category = mysqli_real_escape_string($link, $category);
        $sub_category = mysqli_real_escape_string($link, $sub_category);
        $my_reports = mysqli_real_escape_string($link, $my_reports);
        $filter_internal_reports_only = mysqli_real_escape_string($link, $filter_internal_reports_only);

        $keywords = "%$keywords%";

        if($status == 0)
        {
            $status_SQL = " AND status_id != ?";
        }
        else
        {
        	$status_SQL = " AND status_id = ?";
        }

        if($sub_category == 0)
        {
            $sub_category_SQL = " AND sub_category_id != ?";
        }
        else
        {
        	$sub_category_SQL = " AND sub_category_id = ?";
        }

        if($category == 0)
        {
            $category_SQL = " AND category_id != ?";
        }
        else
        {
        	$category_SQL = " AND category_id = ?";
        }

        if($my_reports == 1)
        {
            $my_report_SQL = " AND creator_id = " . \Ilch\Registry::get('user')->getID();
        }
        else
        {
        	$my_report_SQL = "";
        }

        if ($filter_internal_reports_only == 1 && \Ilch\Registry::get('user')->isAdmin())
        {
            $internal_reports_only_SQL = " AND intern_only = 1";
        }
        else
        {
        	$internal_reports_only_SQL = "";
        }

        if(!\Ilch\Registry::get('user')->isAdmin())
        {
            $internal_reports_only_SQL = " AND intern_only = 0";
        }


        $query = "SELECT [prefix]_bugs.`id`, `sub_category_id`, [prefix]_bugtracker_sub_categories.name AS sub_category_name, [prefix]_bugtracker_sub_categories.category_id,
	                  [prefix]_bugtracker_categories.name AS category_name, `title`, `description`, `priority`, `creator_id`,
	                  `progress`, `status_id`, [prefix]_bugtracker_status.name AS status_name, [prefix]_bugtracker_status.css_class AS status_css_class, `intern_only`, `update_time`, `create_time`
                  FROM [prefix]_bugs
                  JOIN [prefix]_bugtracker_status
	                  ON [prefix]_bugs.status_id = [prefix]_bugtracker_status.id
                  JOIN [prefix]_bugtracker_sub_categories
	                  ON [prefix]_bugs.sub_category_id = [prefix]_bugtracker_sub_categories.id
                  JOIN [prefix]_bugtracker_categories
                  ON [prefix]_bugtracker_sub_categories.category_id = [prefix]_bugtracker_categories.id
                  WHERE (title LIKE '$keywords' OR description LIKE '$keywords')" . $status_SQL . $sub_category_SQL . $category_SQL . $my_report_SQL . $internal_reports_only_SQL;
        $query = $this->db()->getSqlWithPrefix($query);
        $stmt = $link->prepare($query);
        $stmt->bind_param('iii', $status, $sub_category, $category);

        //\Ilch\DebugBar::getInstance()->getCollector('messages')
        $stmt->execute();

        $res = $stmt->get_result();

        $i = 0;
        $bugs = array();

        while($row = mysqli_fetch_assoc($res))
        {
            $bugID = $row['id'];
            $subCategory = new SubCategoryModel($row['sub_category_id'], new CategoryModel($row['category_id'], $row['category_name']), $row['sub_category_name']);
            $priority = $row['priority'];
            $title = $row['title'];
            $description = $row['description'];
            $user = $userMapper->getUserById($row['creator_id']);
            $assignedUsers = $this->getAssignedUsers($bugID);
            $progress = $row['progress'];
            $status = new StatusModel($row['status_id'], $row['status_name'], $row['status_css_class']);
            $likes = $this->getLikes($bugID);
            $dislikes = $this->getDislikes($bugID);
            $internOnly = $row['intern_only'];
            $updateTime = new Date($row['update_time']);
            $createTime = new Date($row['create_time']);

            $comments = $commentMapper->getAllCommentsByBugID($bugID);

            $bugs[$i] = new BugModel($bugID, $subCategory, $priority, $title, $description, $user, $assignedUsers, $progress, $status, $likes, $dislikes, $internOnly, $updateTime, $createTime, $comments);
            $i++;
        }

        return $bugs;
    }

    public function saveBug($bugID, $subCategory, $title, $description, $priority, $progress, $statusID, $internOnly)
    {
        $link = $this->db()->getLink();

        $bugID = mysqli_real_escape_string($link, $bugID);
        $title = mysqli_real_escape_string($link, $title);
        $description = mysqli_real_escape_string($link, $description);
        $subCategory = mysqli_real_escape_string($link, $subCategory);
        $priority = mysqli_real_escape_string($link, $priority);
        $progress = mysqli_real_escape_string($link, $progress);
        $statusID = mysqli_real_escape_string($link, $statusID);
        $internOnly = mysqli_real_escape_string($link, $internOnly);


        $query = "UPDATE [prefix]_bugs SET
                    `sub_category_id` = ?,
                    `title`= ?,
                    `description`= ?,
                    `priority`= ?,
                    `progress`= ?,
                    `status_id`= ?,
                    `intern_only`= ?
                  WHERE `id` = ?";
        $query = $this->db()->getSqlWithPrefix($query);
        $stmt = $link->prepare($query);
        $stmt->bind_param('issiiiii', $subCategory, $title, $description, $priority, $progress, $statusID, $internOnly, $bugID);
        $stmt->execute();
    }

    public function createBug($subCategory, $title, $description, $priority, $creatorID, $progress, $statusID, $internOnly)
    {
        $link = $this->db()->getLink();

        $title = mysqli_real_escape_string($link, $title);
        $description = mysqli_real_escape_string($link, $description);
        $subCategory = mysqli_real_escape_string($link, $subCategory);
        $priority = mysqli_real_escape_string($link, $priority);
        $creatorID = mysqli_real_escape_string($link, $creatorID);
        $progress = mysqli_real_escape_string($link, $progress);
        $statusID = mysqli_real_escape_string($link, $statusID);
        $internOnly = mysqli_real_escape_string($link, $internOnly);

        $query = "INSERT INTO [prefix]_bugs (`sub_category_id`, `title`, `description`, `priority`, `creator_id`, `progress`, `status_id`, `intern_only`, `update_time`, `create_time`) VALUES
                  (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        $query = $this->db()->getSqlWithPrefix($query);
        $stmt = $link->prepare($query);
        $stmt->bind_param('issiiiii', $subCategory, $title, $description, $priority, $creatorID, $progress, $statusID, $internOnly);
        $stmt->execute();

        return $this->db()->getLastInsertId();
    }

    public function deleteBug($bugID)
    {
        $bugID = mysqli_real_escape_string($this->db()->getLink(), $bugID);
        $query = "DELETE FROM [prefix]_bugtracker_assigned_users WHERE bug_id = $bugID";
        $this->db()->query($query);
        
        $bugID = mysqli_real_escape_string($this->db()->getLink(), $bugID);
        $query2 = "DELETE FROM [prefix]_bugtracker_comments WHERE bug_id = $bugID";
        $this->db()->query($query2);
        
        $bugID = mysqli_real_escape_string($this->db()->getLink(), $bugID);
        $query3 = "DELETE FROM [prefix]_bugtracker_votes WHERE bug_id = $bugID";
        $this->db()->query($query3);
        
        $bugID = mysqli_real_escape_string($this->db()->getLink(), $bugID);
        $query4 = "DELETE FROM [prefix]_bugs WHERE id = $bugID";
        $this->db()->query($query4);
    }

    public function removeLikeDislikeFromBug($bugID, $userID)
    {
        $link = $this->db()->getLink();

        $bugID = mysqli_real_escape_string($link, $bugID);
        $userID = mysqli_real_escape_string($link, $userID);

        $query = "DELETE FROM [prefix]_bugtracker_votes WHERE bug_id = ? AND user_id = ?";
        $query = $this->db()->getSqlWithPrefix($query);
        $stmt = $link->prepare($query);
        $stmt->bind_param('ii', $bugID, $userID);
        $stmt->execute();
    }

    public function likeBug($bugID, $userID)
    {
        $link = $this->db()->getLink();

        $bugID = mysqli_real_escape_string($link, $bugID);
        $userID = mysqli_real_escape_string($link, $userID);

        $query = "INSERT INTO [prefix]_bugtracker_votes (bug_id, user_id, type) VALUES (?, ?, 'like')";
        $query = $this->db()->getSqlWithPrefix($query);
        $stmt = $link->prepare($query);
        $stmt->bind_param('ii', $bugID, $userID);
        $stmt->execute();
    }

    public function dislikeBug($bugID, $userID)
    {
        $link = $this->db()->getLink();

        $bugID = mysqli_real_escape_string($link, $bugID);
        $userID = mysqli_real_escape_string($link, $userID);

        $query = "INSERT INTO [prefix]_bugtracker_votes (bug_id, user_id, type) VALUES (?, ?, 'dislike')";
        $query = $this->db()->getSqlWithPrefix($query);
        $stmt = $link->prepare($query);
        $stmt->bind_param('ii', $bugID, $userID);
        $stmt->execute();
    }

    public function userIsLiker($bugID, $userID)
    {
        $link = $this->db()->getLink();

        $bugID = mysqli_real_escape_string($link, $bugID);
        $userID = mysqli_real_escape_string($link, $userID);

        $query = "SELECT * FROM [prefix]_bugtracker_votes WHERE user_id = ? AND bug_id = ? AND type = 'like'";
        $query = $this->db()->getSqlWithPrefix($query);
        $stmt = $link->prepare($query);
        $stmt->bind_param('ii', $userID, $bugID);
        $stmt->execute();

        $res = $stmt->get_result();

        if(mysqli_num_rows($res) > 0)
        {
            return true;
        }

        return false;
    }

    public function userIsDisliker($bugID, $userID)
    {
        $link = $this->db()->getLink();

        $bugID = mysqli_real_escape_string($link, $bugID);
        $userID = mysqli_real_escape_string($link, $userID);

        $query = "SELECT * FROM [prefix]_bugtracker_votes WHERE user_id = ? AND bug_id = ? AND type = 'dislike'";
        $query = $this->db()->getSqlWithPrefix($query);
        $stmt = $link->prepare($query);
        $stmt->bind_param('ii', $userID, $bugID);
        $stmt->execute();

        $res = $stmt->get_result();

        if(mysqli_num_rows($res) > 0)
        {
            return true;
        }

        return false;
    }

    private function getLikes($bugID)
    {
        $link = $this->db()->getLink();
        $bugID = mysqli_real_escape_string($link, $bugID);

        $query = "SELECT * FROM [prefix]_bugtracker_votes WHERE bug_id = ? AND type = 'like'";
        $query = $this->db()->getSqlWithPrefix($query);
        $stmt = $link->prepare($query);
        $stmt->bind_param('i', $bugID);
        $stmt->execute();

        $res = $stmt->get_result();

        $likes = array();
        $i = 0;
        while ($row = mysqli_fetch_assoc($res))
        {
            $likes[$i] = $row['user_id'];
            $i++;
        }

        return $likes;
    }

    private function getDislikes($bugID)
    {
        $link = $this->db()->getLink();
        $bugID = mysqli_real_escape_string($link, $bugID);

        $query = "SELECT * FROM [prefix]_bugtracker_votes WHERE bug_id = ? AND type = 'dislike'";
        $query = $this->db()->getSqlWithPrefix($query);
        $stmt = $link->prepare($query);
        $stmt->bind_param('i', $bugID);
        $stmt->execute();

        $res = $stmt->get_result();

        $dislikes = array();
        $i = 0;
        while ($row = mysqli_fetch_assoc($res))
        {
            $dislikes[$i] = $row['user_id'];
            $i++;
        }

        return $dislikes;
    }

    private function getAssignedUsers($bugID)
    {
        $link = $this->db()->getLink();
        $bugID = mysqli_real_escape_string($link, $bugID);

        $query = "SELECT * FROM [prefix]_bugtracker_assigned_users WHERE bug_id = ?";
        $query = $this->db()->getSqlWithPrefix($query);
        $stmt = $link->prepare($query);
        $stmt->bind_param('i', $bugID);
        $stmt->execute();

        $res = $stmt->get_result();

        $users = array();
        $i = 0;
        while ($row = mysqli_fetch_assoc($res))
        {
            $users[$i] = $row['user_id'];
            $i++;
        }

        return $users;
    }

    public function addAssignee($bugID, $userID)
    {
        $link = $this->db()->getLink();

        $query = "INSERT INTO [prefix]_bugtracker_assigned_users (bug_id, user_id) VALUES (?, ?)";
        $query = $this->db()->getSqlWithPrefix($query);
        $stmt = $link->prepare($query);

        $stmt->bind_param('ii', $bugID, $userID);
        $stmt->execute();
    }

    public function removeAssignee($bugID, $userID)
    {
        $link = $this->db()->getLink();

        $query = "DELETE FROM [prefix]_bugtracker_assigned_users WHERE bug_id = ? AND user_id = ?";
        $query = $this->db->getSqlWithPrefix($query);
        $stmt = $link->prepare($query);

        $stmt->bind_param('ii', $bugID, $userID);
        $stmt->execute();
    }
}
