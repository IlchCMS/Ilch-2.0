<?php
$bug = $this->get('bug');
$user = $this->get('user');
$userMapper = $this->get('userMapper');

if($bug->isInternOnly() && !isset($user))
{
    echo "<h1 class='text-center'>Sorry, Bug not found.</h1>";
    return;
}

if(($bug->isInternOnly() && !$user->isAdmin()))
{
    echo "<h1 class='text-center'>Sorry, Bug not found.</h1>";
    return;
}
?>

<link href="http://davidstutz.github.io/bootstrap-multiselect/dist/css/bootstrap-multiselect.css" rel="stylesheet">

    <div class="col-md-9">
       <div class="boxes">
           <div class="pad10">
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="dividerO">
                            <?php echo (($bug->isInternOnly()) ? '<span class="label label-primary">Intern</span> ' : '') . $bug->getTitle(); ?>
                            <small>#<?php echo $bug->getID();?></small>
                            <?php
                                if(isset($user) && ($user->isAdmin() || $user->isQA()))
                                {
                                    echo "<a class='btn btn-sm btn-default' href='{$this->getUrl(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'edit', 'bug-id' => $bug->getID()])}'>Edit</a> ";
                                    echo "<a class='btn btn-sm btn-danger' href='{$this->getUrl(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'delete', 'bug-id' => $bug->getID()])}''>Delete</a> ";
                                }
                                if(isset($user))echo "<a class='btn btn-sm btn-success' href='{$this->getUrl(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'new'])}'>New Issue</a>";?>
                        </h1>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                           <div class="text-center">
                            <?php echo "<span class='{$bug->getStatus()->getCssClass()}'>{$bug->getStatus()->getName()}</span>"; ?>                                        
                            <?php echo "<a href='#'>{$bug->getUser()->getName()}</a> openend this issue at {$bug->getCreateTime()} &middot; " . count($bug->getComments()) . " comment(s)"; ?>
                           </div>                
                        <hr />
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-sm-6">
                                         <?php echo "<a href='#'>{$bug->getUser()->getName()}</a> created at {$bug->getCreateTime()}" ?>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                         <span class="label label-default">Reporter</span>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-bodyBugtracker"><?php echo $bug->getDescription(); ?></div>
                        </div>
                        <?php
                            foreach($bug->getComments() as $comment)
                            {
                                if($comment->isInternOnly() && !isset($user))
                                    continue;

                                if(($comment->isInternOnly() && (!$user->isAdmin() || !$user->isQA())))
                                    continue;

                                echo "<div class='panel panel-default'>
                                            <div class='panel-heading'>
                                                <div class='row'>
                                                    <div class='col-sm-6'>
                                                        <p>
                                                            <a href='#'>{$comment->getUser()->getName()}</a> commented at {$comment->getCreateTime()}
                                                        </p>
                                                    </div>
                                                    <div class='col-sm-6 text-right'>
                                                        
                                                            " . (($comment->isInternOnly()) ? "<span class='label label-primary'>Intern</span>" : '') . "
                                                            " . (($comment->getUser()->getID() == $bug->getUser()->getID()) ? "<span class='label label-default'>Reporter</span> " : '');
                                                            if(isset($user) && ($user->isAdmin() || $user->isQA()))
                                                            {
                                                                echo "<a class='btn btn-sm btn-primary' href='{$this->getUrl(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'editComment', 'comment-id' => $comment->getID()])}'><i class='fa fa-pencil' aria-hidden='true'></i></a> ";
                                                                echo "<a class='btn btn-sm btn-danger'  href='{$this->getUrl(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'deleteComment', 'comment-id' => $comment->getID()])}'><i class='fa fa-trash' aria-hidden='true'></i></a>";
                                                            }                                                                                                        
                                                  echo "
                                                    </div>
                                                </div>
                                            </div>
                                        <div class='panel-bodyBugtracker'>{$comment->getContent()}</div>
                                    </div>";
                            }

                        ?>   
                        <?php

                            if(isset($user))
                            {
                                echo "<hr />
                                      <form action='{$this->getUrl(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'comment', 'bug-id' => $bug->getID()])}' method='post'>
                                          {$this->getTokenField()}
                                          <div class='form-group'>
                                              <label for='comment'>Comment:</label>
                                              <textarea class='form-control ckeditor' name='comment' toolbar='ilch_bbcode' required>
                                              </textarea>
                                          </div>
                                          " . (($user->isAdmin() || $user->isQA()) ? "<div class='checkbox'><label><input type='checkbox' name='intern-only' value='1' />Intern only</label></div>" : '') . "
                                          <button type='submit' class='btn btn-default'>Comment</button>
                                      </form>";
                            }

                        ?>

                    </div>
                </div>
           </div>
       </div>
    </div>
    <div class="col-md-3">
       <div class="boxes">
           <div class="pad10">
                <h4>Category</h4>
                <p><?php echo $bug->getSubCategory()->getCategory()->getName() . " > " . $bug->getSubCategory()->getName(); ?></p>
                <hr />
                <h4>Priority: 
                    <?php
                        switch($bug->getPriority())
                        {
                            case 1:
                                echo "<span class='label label-danger'>High</span>";
                                break;
                            case 2:
                                echo "<span class='label label-warning'>Normal</span>";
                                break;
                            case 3:
                                echo "<span class='label label-default'>Low</span>";
                                break;
                        }
                    ?>
                </h4>  
                <hr />
                <h4>Progress:</h4>
                <?php
                echo "<div class='progress'>
                          <div class='progress-bar progress-bar-striped progress-bar-success active' role='progressbar'
                              aria-valuenow='{$bug->getProgress()}' aria-valuemin='0' aria-valuemax='100' style='width:{$bug->getProgress()}%'>
                              {$bug->getProgress()}%
                          </div>
                      </div>";

                ?>

                <hr />        
                <h4>Assignees</h4>
                <?php
                 foreach ($bug->getAssignedUsers() as $assignedUser)
                 {
                     $removeAssigneeLink = "";
                     if(isset($user) && ($user->isAdmin() || $user->isQA()))
                         $removeAssigneeLink = "<a href='{$this->getUrl(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'removeAssignee', 'bug-id' => $bug->getID(), 'user-id' => $assignedUser])}'><i class='fa fa-times'></i></a>";

                     echo "<p>{$userMapper->getUserById($assignedUser)->getName()} $removeAssigneeLink</p>";
                 }

                 if(count($bug->getAssignedUsers()) == 0)
                 {
                     echo "<p>No assigned Users.</p>";
                 }         
                ?>
                <?php if (isset($user) && ($user->isAdmin() || $user->isQA())): ?>  
                    <form action="<?php echo $this->getUrl(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'addAssignee', 'bug-id' => $bug->getID()]) ?>" method="POST">
                        <?php echo $this->getTokenField(); ?>
                        <div class='form-group'>
                            <label>Assign new Users:</label>
                            <select class='form-control' id='assignedUsers' name='user-id' data-placeholder='Select User' required>
                            <?php

                            foreach ($userMapper->getUserList() as $userFormList)
                            {
                                if(!in_array($userFormList->getID(), $bug->getAssignedUsers()))
                                    echo "<option value='{$userFormList->getID()}'>{$userFormList->getName()}</option>";
                            }
                            ?>
                            </select>
                        </div>

                        <button type='submit' class='btn btn-primary'>Assign</button>
                    </form>
                <?php endif;?>             

                <hr />
                <h4>Votes</h4>
                <p>
                    <?php
                    $likeURL = "";
                    $dislikeActive = "";
                    $likeActive = "";
                    $dislikeActive = "";
                    if(isset($user))
                    {
                        $likeURL = $this->getUrl(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'like', 'bug-id' => $bug->getID()]);
                        $dislikeURL = $this->getUrl(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'dislike', 'bug-id' => $bug->getID()]);

                        if(in_array($user->getID(), $bug->getLikes()))
                        {
                            $likeActive = "active";
                        }

                        if(in_array($user->getID(), $bug->getDislikes()))
                        {
                            $dislikeActive = "active";
                        }
                        
                        echo "<a class='btn btn-success $likeActive' href='$likeURL'>
                                    <i class='fa fa-thumbs-up' aria-hidden='true'></i> " . count($bug->getLikes()) . "
                                </a>
                                <a class='btn btn-danger $dislikeActive' href='$dislikeURL'>
                                    <i class='fa fa-thumbs-down' aria-hidden='true'></i> " . count($bug->getDislikes()) . "
                                </a>";
                    } else {
                        echo "
                             <a class='btn btn-success $likeActive'>
                                    <i class='fa fa-thumbs-up' aria-hidden='true'></i> " . count($bug->getLikes()) . "
                                </a>
                                <a class='btn btn-danger $dislikeActive'>
                                    <i class='fa fa-thumbs-down' aria-hidden='true'></i> " . count($bug->getDislikes()) . "
                                </a>";
                    }

                    ?>

                </p>
                <hr />
           </div>
       </div>
    </div>
<script type='text/javascript' src='http://davidstutz.github.io/bootstrap-multiselect/dist/js/bootstrap-multiselect.js'></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#assignedUsers').multiselect({
            enableClickableOptGroups: true
        });
    });
</script>
