<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Bugtracker\Controllers;

use Modules\User\Mappers\User as UserMapper;

use Modules\Bugtracker\Mappers\Bug as BugMapper;
use Modules\Bugtracker\Mappers\Comment as CommentMapper;
use Modules\Bugtracker\Mappers\Status as StatusMapper;
use Modules\Bugtracker\Mappers\Category as CategoryMapper;
use Modules\Bugtracker\Mappers\SubCategory as SubCategoryMapper;

use Modules\Bugtracker\Models\Status as StatusModel;

class Index extends \Ilch\Controller\Frontend
{
    public function init()
    {

    }

    public function indexAction()
    {
        #region Mappers
        $userMapper = new UserMapper();
        $bugMapper = new BugMapper();
        $statusMapper = new StatusMapper();
        $categoryMapper = new CategoryMapper();
        $subCategoryMapper = new SubCategoryMapper();
        #endregion

        $filter_keywords = $this->getRequest()->getPost('keywords');
        $filter_status = $this->getRequest()->getPost('status');
        $filter_category = $this->getRequest()->getPost('category');
        $filter_sub_category = $this->getRequest()->getPost('sub-category');
        $filter_my_reports = $this->getRequest()->getPost('my-reports-only');
        $filter_internal_reports_only = $this->getRequest()->getPost('internal-reports-only');
        
        $user = $this->getUser();

        if(count($this->getRequest()->getPost()) == 0)
        {
            $filter_keywords = "";
            $filter_status = 0;
            $filter_category = 0;
            $filter_sub_category = 0;
            $filter_my_reports = 0;
            $filter_internal_reports_only = 0;

            $bugs = $bugMapper->getAllBugs();
        }
        else
        {
            if(isset($user) && ($user->isAdmin() || $user->isQA()))
            {
                $filter_internal_reports_only = 0;
            }

            $bugs = $bugMapper->getAllBugsByFilter($filter_keywords, $filter_status, $filter_category, $filter_sub_category, $filter_my_reports, $filter_internal_reports_only);
        }


        $status = $statusMapper->getAllStatus();
        $categories = $categoryMapper->getAllCategories();
        $subCategories = $subCategoryMapper->getAllSubCategories();

        $this->getView()->set('bugs', $bugs);
        $this->getView()->set('status', $status);
        $this->getView()->set('categories', $categories);
        $this->getView()->set('subCategories', $subCategories);

        $this->getView()->set('filter-keywords', $filter_keywords);
        $this->getView()->set('filter-status', $filter_status);
        $this->getView()->set('filter-category', $filter_category);
        $this->getView()->set('filter-sub-category', $filter_sub_category);
        $this->getView()->set('filter-my-reports-only', $filter_my_reports);
        $this->getView()->set('filter-internal-reports-only', $filter_internal_reports_only);
    }

    public function showAction()
    {
        #region Mappers
        $bugMapper = new BugMapper();
        $userMapper = new UserMapper();
        #endregion

        if($this->getRequest()->getParam('bug-id') == null)
        {
            $this->redirect(['controller' => 'index', 'action' => 'index']);
            return;
        }

        $bugID = $this->getRequest()->getParam('bug-id');

        $bug = $bugMapper->getBugByID($bugID);

        $this->getView()->set('bug', $bug);
        $this->getView()->set('userMapper', $userMapper);
        $this->getView()->set('user', $this->getUser());
    }

    public function editAction()
    {
        $user = $this->getUser();

        #region Mappers
        $bugMapper = new BugMapper();
        $statusMapper = new StatusMapper();
        $categoryMapper = new CategoryMapper();
        $subCategoryMapper = new SubCategoryMapper();
        #endregion

        $bugID = $this->getRequest()->getParam('bug-id');

        if(!isset($bugID))
            $this->redirect(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'index']);


        if(isset($user) && ($user->isAdmin() || $user->isQA())) //und evtl der Ersteller
        {
            $bug = $bugMapper->getBugByID($bugID);

            $status = $statusMapper->getAllStatus();
            $categories = $categoryMapper->getAllCategories();
            $subCategories = $subCategoryMapper->getAllSubCategories();

            $this->getView()->set('bug', $bug);
            $this->getView()->set('status', $status);
            $this->getView()->set('categories', $categories);
            $this->getView()->set('subCategories', $subCategories);
        }
        else
        {
            $this->redirect(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'show', 'bug-id' => $bugID]);
        }
    }

    public function saveEditAction()
    {
        $user = $this->getUser();

        $bugMapper = new BugMapper();

        $bugID = $this->getRequest()->getParam('bug-id');

        if(!isset($bugID))
            $this->redirect(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'index']);


        if(isset($user) && ($user->isAdmin() || $user->isQA())) //und evtl der Ersteller
        {
            $title = $this->getRequest()->getPost('title');
            $category = $this->getRequest()->getPost('category');
            $subCategory = $this->getRequest()->getPost('sub-category');
            $description = $this->getRequest()->getPost('description');

            //Abhängig davon ob Admin oder net:
            $status = $this->getRequest()->getPost('status');
            $progress = $this->getRequest()->getPost('progress');
            $priority = $this->getRequest()->getPost('priority');
            $internOnly = $this->getRequest()->getPost('intern-only');

            if(!isset($status))
                $status = StatusModel::NEW_REPORT;

            if(!isset($priority) || $progress < 0)
                $progress = 0;

            if($progress > 100)
                $progress = 100;

            if(!isset($priority) || $priority < 1 || $priority > 3)
                $priority = 2;

            if(!isset($internOnly))
                $internOnly = false;

            if(!$user->isAdmin())
            {
                $status = StatusModel::NEW_REPORT;
                $progress = 0;
                $priority = 2;
                $internOnly = false;
            }

            switch($status)
            {
                //handle status changes witch require other changes e.g. progress
            }

            //var_dump($bugID, $subCategory, $title, $description, $priority, $progress, $status, $internOnly);
            if(isset($title) && isset($category) && isset($subCategory) && isset($description) && isset($status) && isset($progress) && isset($priority) && isset($internOnly))
            {
                $bugMapper->saveBug($bugID, $subCategory, $title, $description, $priority, $progress, $status, $internOnly);
                $this->redirect(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'show', 'bug-id' => $bugID]);
            }
            else
            {
            	$this->redirect(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'index']);
            }
        }
    }

    public function newAction()
    {
        $user = $this->getUser();

        #region Mappers
        $statusMapper = new StatusMapper();
        $categoryMapper = new CategoryMapper();
        $subCategoryMapper = new SubCategoryMapper();
        #endregion

        $status = $statusMapper->getAllStatus();
        $categories = $categoryMapper->getAllCategories();
        $subCategories = $subCategoryMapper->getAllSubCategories();

        $this->getView()->set('status', $status);
        $this->getView()->set('categories', $categories);
        $this->getView()->set('subCategories', $subCategories);
    }

    public function createIssueAction()
    {
        $user = $this->getUser();

        $bugMapper = new BugMapper();

        if(!isset($user))
            $this->redirect(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'index']);


        $title = $this->getRequest()->getPost('title');
        $category = $this->getRequest()->getPost('category');
        $subCategory = $this->getRequest()->getPost('sub-category');
        $description = $this->getRequest()->getPost('description');

        //Abhängig davon ob Admin oder net:
        $status = $this->getRequest()->getPost('status');
        $progress = $this->getRequest()->getPost('progress');
        $priority = $this->getRequest()->getPost('priority');
        $internOnly = $this->getRequest()->getPost('intern-only');

        if(!isset($status))
            $status = StatusModel::NEW_REPORT;

        if(!isset($priority) || $progress < 0)
            $progress = 0;

        if($progress > 100)
            $progress = 100;

        if(!isset($priority) || $priority < 1 || $priority > 3)
            $priority = 2;

        if(!isset($internOnly))
            $internOnly = false;

        if(!$user->isAdmin())
        {
            $status = StatusModel::NEW_REPORT;
            $progress = 0;
            $priority = 2;
            $internOnly = false;
        }

        if(isset($title) && isset($category) && isset($subCategory) && isset($description) && isset($status) && isset($progress) && isset($priority) && isset($internOnly))
        {
            $bugID = $bugMapper->createBug($subCategory, $title, $description, $priority, $user->getId(), $progress, $status, $internOnly);
            $this->redirect(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'show', 'bug-id' => $bugID]);
        }
        else
        {
            $this->redirect(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'index']);
        }
    }

    public function deleteAction()
    {
        $bugID = $this->getRequest()->getParam('bug-id');
        $user = $this->getUser();
        $bugMapper = new BugMapper();
        
        if(isset($bugID) && $user->isAdmin() || $user->isQA())
        {
            $bugMapper->deleteBug($bugID);
            $this->addMessage("deleteSuccess");
            $this->redirect(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'index']);
        }
        else {
            $this->addMessage('ERROR : You cant delete Bugs', 'danger');
            $this->redirect(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'index']);
        }  
    }

    public function commentAction()
    {
        $commentMapper = new CommentMapper();

        $user = $this->getUser();

        $comment = $this->getRequest()->getPost('comment');
        $bugID = $this->getRequest()->getParam('bug-id');
        $internOnly = $this->getRequest()->getPost('intern-only');

        if(!isset($bugID))
        {
            $this->redirect(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'index']);
        }


        if($comment != null && $comment != "" && isset($user))
        {
            if(!isset($internOnly))
                $internOnly = false;

            if(!$user->isAdmin())
                $internOnly = false;

            $internOnly = (bool)$internOnly;
            $commentMapper->addComment($bugID, $comment, $user->getID(), $internOnly);
            $this->redirect(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'show', 'bug-id' => $bugID]);
        }
        else
        {
            echo "comment not set or not logged in";
        	$this->redirect(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'show', 'bug-id' => $bugID]);
        }
    }

    public function addAssigneeAction()
    {
        $bugID = $this->getRequest()->getParam('bug-id');
        $assigneeID = $this->getRequest()->getPost('user-id');

        if(isset($bugID) && isset($assigneeID) && $this->getUser()->isAdmin() || $this->getUser()->isQA())
        {
            $bugMapper = new BugMapper();
            $bugMapper->addAssignee($bugID, $assigneeID);

            $this->redirect(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'show', 'bug-id' => $bugID]);
            return;
        }
    }

    public function removeAssigneeAction()
    {
        $bugID = $this->getRequest()->getParam('bug-id');
        $assigneeID = $this->getRequest()->getParam('user-id');

        if(isset($bugID) && isset($assigneeID) && $this->getUser()->isAdmin() || $this->getUser()->isQA())
        {
            $bugMapper = new BugMapper();
            $bugMapper->removeAssignee($bugID, $assigneeID);

            $this->redirect(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'show', 'bug-id' => $bugID]);
            return;
        }

        $this->redirect(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'index']);
    }

    public function likeAction()
    {
        $userID = $this->getUser()->getId();
        $bugID = $this->getRequest()->getParam('bug-id');

        $bugMapper = new BugMapper();
        $bug = $bugMapper->getBugByID($bugID);

        $userWasLiker = $bugMapper->userIsLiker($bugID, $userID);

        $bugMapper->removeLikeDislikeFromBug($bugID, $userID);

        if(!$userWasLiker)
            $bugMapper->likeBug($bugID, $userID);

        $this->redirect(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'show', 'bug-id' => $bug->getID()]);
    }

    public function dislikeAction()
    {
        $userID = $this->getUser()->getId();
        $bugID = $this->getRequest()->getParam('bug-id');

        $bugMapper = new BugMapper();
        $bug = $bugMapper->getBugByID($bugID);

        $userWasDisliker = $bugMapper->userIsDisliker($bugID, $userID);

        $bugMapper->removeLikeDislikeFromBug($bugID, $userID);

        if(!$userWasDisliker)
            $bugMapper->dislikeBug($bugID, $userID);

        $this->redirect(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'show', 'bug-id' => $bug->getID()]);
    }

    public function editCommentAction()
    {
        $commentMapper = new CommentMapper();

        $commentID = $this->getRequest()->getParam('comment-id');
        $comment = $commentMapper->getCommentByID($commentID);

        $user = $this->getUser();

        if(isset($user) && ($comment->getUser()->getID() == $user->getID() || $user->isAdmin() || $user->isQA()))
        {
            $this->getView()->set('comment', $comment);
        }
        else
        {
            if(isset($comment))
            {
                $this->redirect(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'show', 'bug-id' => $comment->getBugID()]);
            }
            else
            {
            	$this->redirect(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'index']);
            }
        }
    }

    public function saveCommentAction()
    {
        $user = $this->getUser();

        $commentID = $this->getRequest()->getParam('comment-id');
        $content = $this->getRequest()->getPost('content');
        $content = nl2br($content);
        $internOnly = $this->getRequest()->getPost('intern-only');

        if(isset($user) && isset($commentID) && isset($content))
        {
            $commentMapper = new CommentMapper();
            $comment = $commentMapper->getCommentByID($commentID);

            if(!($comment->getUser()->getID() == $user->getID() || $user->isAdmin() || $user->isQA()))
                $this->redirect(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'show', 'bug-id' => $comment->getBugID()]);

            if(!isset($internOnly))
                $internOnly = 0;

            $commentMapper->saveComment($commentID, $content, $internOnly);

            $this->redirect(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'show', 'bug-id' => $comment->getBugID()]);
        }
    }

    public function deleteCommentAction()
    {
        $user = $this->getUser();
        $commentID = $this->getRequest()->getParam('comment-id');
        if(isset($commentID) && $user->isAdmin() || $user->isQA())
        {
            $commentMapper = new CommentMapper();
            $comment = $commentMapper->getCommentByID($commentID);
            $commentMapper->deleteComment($commentID);

            $this->redirect(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'show', 'bug-id' => $comment->getBugID()]);
        }
    }
}
