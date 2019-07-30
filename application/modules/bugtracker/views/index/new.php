<?php
    $user = \Ilch\Registry::get('user');

    $status = $this->get('status');
    $categories = $this->get('categories');
    $subCategories = $this->get('subCategories');

    if(!isset($user))
    {
        echo "<h1 class='text-center'>Sorry, Site not found.</h1>";
        return;
    }
?>

<h1>Report a new Issue</h1> 
<form action="<?php echo $this->getUrl(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'createIssue']); ?>" method="post">
    <?php echo $this->getTokenField(); ?>
    <div class="form-group">
        <label>Title</label>
        <input type="text" class="form-control" name="title" required/>
    </div>           
    <div class="form-group row">
        <div class="col-xs-6">
            <label>Category</label>
            <select name="category" class="form-control" required>

                <?php
                foreach ($categories as $c)
                {
                    echo "<option value='{$c->getID()}'>{$c->getName()}</option>";
                }
                ?>

            </select>
        </div>
        <div class="col-xs-6">
            <label>Sub-Category</label>
            <select name="sub-category" class="form-control" required>

                <?php
                foreach ($subCategories as $sc)
                {
                    echo "<option data-parent-id='{$sc->getCategory()->getID()}' value='{$sc->getID()}'>{$sc->getName()}</option>";
                }
                ?>

            </select>
        </div>             
    </div>
    <div class='form-group'>
        <label for='comment'>Description</label>
        <textarea class='form-control ckeditor' name='description' toolbar='ilch_bbcode' required></textarea>
    </div>
    <?php if($user->isAdmin()): ?>
    <div class="form-group">
        <label>Status</label>
        <select name="status" class="form-control" required>

            <?php
              foreach ($status as $s)
              {
                  echo "<option value='{$s->getID()}'>{$s->getName()}</option>";
              }
            ?>

        </select>
    </div>
    <div class="form-group row">
        <div class="col-xs-2">
            <label>Progress</label>
            <input class="form-control" type="number" name="progress" min="0" max="100" required/>
        </div>
        <div class="col-xs-2">
            <label>Priority</label>
            <select name="priority" class="form-control" required>                  
                <option value="1">High</option>
                <option value="2" selected>Normal</option>
                <option value="3">Low</option>
            </select>
        </div>        
    </div>
    <div class='checkbox'>
        <label>
            <input type='checkbox' name='intern-only' value='1'/>Intern only
        </label>
    </div> 
    <?php endif; ?>
    <button type="submit" class="btn btn-default">Report Issue</button>
</form>