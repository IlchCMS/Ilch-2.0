<?php
    $bugs = $this->get('bugs');
    $status = $this->get('status');
    $categories = $this->get('categories');
    $subCategories = $this->get('subCategories');

    $filter_keywords = $this->get('filter-keywords');
    $filter_status = $this->get('filter-status');
    $filter_category = $this->get('filter-category');
    $filter_sub_category = $this->get('filter-sub-category');
    $filter_my_reports = $this->get('filter-my-reports-only');
    $filter_internal_reports_only = $this->get('filter-internal-reports-only');

    $user = \Ilch\Registry::get('user');    
?>

<div class="dividerO">
    <h1>Bugtracker <a class='btn btn-sm btn-success' href='<?php echo $this->getUrl(['module'=> 'bugtracker', 'controller' => 'index', 'action' => 'new']); ?>'>New Issue</a></h1>
</div>  
<div class="row">
    <div class="col-sm-12 boxes mb-3">
       <div class="pad10 text-center">
        <form class="form-inline" method="POST">
            <?php echo $this->getTokenField(); ?>
            <div class="form-group">
                <label>Keywords</label>
                <input name="keywords" type="text" class="form-control" value="<?php echo $filter_keywords; ?>" />
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="0">All Status</option>

                    <?php
                        foreach ($status as $s)
                        {                                                        
                            echo "<option value='{$s->getID()}' " . (($s->getID() == $filter_status) ? 'selected': '') . ">{$s->getName()}</option>";
                        }
                    ?>
                    
                </select>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category" class="form-control">                    
                    <option value="0">All Categories</option>

                    <?php
                    foreach ($categories as $c)
                    {
                        echo "<option value='{$c->getID()}' " . (($c->getID() == $filter_category) ? 'selected': '') . ">{$c->getName()}</option>";
                    }
                    ?>
                    
                </select>
            </div>
            <div class="checkbox">
                <label>
                    <input name="my-reports-only" type="checkbox" value="1" <?php echo (($filter_my_reports == 1) ? 'checked': ''); ?>/> My reports only
                </label>
            </div>
            <?php            
            if(isset($user) && $user->isAdmin())
            {
                echo "<div class='checkbox'>
                        <label>
                            <input name='internal-reports-only' type='checkbox' value='1' " . (($filter_internal_reports_only == 1) ? 'checked': '') . "/> Internal Reports only
                        </label>
                     </div>"; 
            }
            ?>
            
            <button type="submit" class="btn btn-sm btn-success">Filter</button>
        </form>
       </div>
    </div>        
</div>
<div class="row">
    <div class="col-sm-12 boxes pad10">
        <table class="table table-striped" id="buglist">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Updated</th>
                    <th>Created By</th>
                    <th>Created</th>
                    <th>Rating</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($bugs as $bug)
                    {                        
                        echo "<tr style='min-height:50px;border-bottom:1px solid #749474;'>
                                  <td>{$bug->getID()}</td>
                                  <td>{$bug->getTitle()}</td>
                                  <td>{$bug->getSubCategory()->getCategory()->getName()} > {$bug->getSubCategory()->getName()}</td>
                                  <td>{$bug->getStatus()->getName()}</td>
                                  <td>{$bug->getPriority()}</td>
                                  <td>{$bug->getUpdateTime()}</td>
                                  <td>{$bug->getUser()->getName()}</td>
                                  <td>{$bug->getCreateTime()}</td>
                                  <td><i class='fa fa-thumbs-up' aria-hidden='true'></i>: " . count($bug->getLikes()) . " | <i class='fa fa-thumbs-down' aria-hidden='true'>: " . count($bug->getDislikes()) . "</td>
                                  <td><a class='btn btn-primary' href='/index.php/bugtracker/index/show/bug-id/{$bug->getID()}'>More</td>
                              </tr>";
                    }
                ?>                
            </tbody>
        </table>
    </div>        
</div>

<?php
    //var_dump($this->get('bugs'));
?>


<script>
    $(document).ready(function(){
        $('#buglist').DataTable({
            "order": [[ 0, "desc" ]],
            searching: false,
            "pageLength": 20
        });
    });
</script>