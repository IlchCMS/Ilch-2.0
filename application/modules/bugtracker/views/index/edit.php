<?php
    $user = \Ilch\Registry::get('user');

    $bug = $this->get('bug');
    $status = $this->get('status');
    $categories = $this->get('categories');
    $subCategories = $this->get('subCategories');

    if(!isset($user))
    {
        echo "<h1 class='text-center'>Sorry, Site not found.</h1>";
        return;
    }
?>

<h1>Edit a Issue</h1> 
<form action="<?php echo $this->getUrl(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'saveEdit', 'bug-id' => $bug->getID()]); ?>" method="post">
    <?php echo $this->getTokenField(); ?>
    <div class="form-group">
        <label>Title</label>
        <input type="text" class="form-control" name="title" value="<?php echo $bug->getTitle(); ?>" required/>
    </div>           
    <div class="form-group row">
        <div class="col-xs-6">
            <label>Category</label>
            <select name="category" class="form-control" required>

                <?php
                foreach ($categories as $c)
                {
                    echo "<option value='{$c->getID()}' " . (($c->getID() == $bug->getSubCategory()->getCategory()->getID()) ? 'selected': '') . ">{$c->getName()}</option>";
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
                    echo "<option data-parent-id='{$sc->getCategory()->getID()}' value='{$sc->getID()}' " . (($sc->getID() == $bug->getSubCategory()->getID()) ? 'selected': '') . ">{$sc->getName()}</option>";
                }
                ?>

            </select>
        </div>             
    </div>
    <div class='form-group'>
        <label for='comment'>Description</label>
        <textarea class='form-control ckeditor' name='description' toolbar='ilch_bbcode' required><?php echo nl2br($bug->getDescription()); ?></textarea>
    </div>
    <?php if($user->isAdmin() or $user->isQA()): ?>
    <div class="form-group">
        <label>Status</label>
        <select name="status" class="form-control" required>

            <?php
            foreach ($status as $s)
            {
                echo "<option value='{$s->getID()}' " . (($s->getID() == $bug->getStatus()->getID()) ? 'selected': '') . ">{$s->getName()}</option>";
            }
            ?>

        </select>
    </div>
    <div class="form-group row">
        <div class="col-xs-2">
            <label>Progress</label>
            <input class="form-control" type="number" name="progress" min="0" max="100" value="<?php echo $bug->getProgress(); ?>" required/>
        </div>
        <div class="col-xs-2">
            <label>Priority</label>
            <select name="priority" class="form-control" required>                  
                <option value="1" <?php echo (($bug->getPriority() == 1) ? 'selected' : '') ?>>High</option>
                <option value="2" <?php echo (($bug->getPriority() == 2) ? 'selected' : '') ?>>Normal</option>
                <option value="3" <?php echo (($bug->getPriority() == 3) ? 'selected' : '') ?>>Low</option>
            </select>
        </div>        
    </div>
    <div class='checkbox'>
        <label>
            <input type='checkbox' name='intern-only' value='1' <?php echo (($bug->isInternOnly()) ? 'checked' : '');?>/>Intern only
        </label>
    </div> 
    <?php endif; ?>
    <button type="submit" class="btn btn-default">Save changes</button>
</form>