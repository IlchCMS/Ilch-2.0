<form class="form-horizontal" 
      action="" 
      method="post">
   <?php echo $this->getTokenField(); ?>
    <div class="form-group">
        <div class="col-lg-12">
            <input class="form-control"
                   id="name"
                   name="name"
                   type="text"
                   placeholder="Name"
                   value="<?php if($this->getUser() !== null) { echo $this->escape($this->getUser()->getName());} ?>"
                   required />
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-12">
            <textarea class="form-control"
                      name="textarea" 
                      id="x"
                      cols="10" 
                      rows="5"
                      maxlength="50"
                      placeholder="<?php echo $this->trans('message'); ?>"
                      required></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-12">
            <button type="submit" name="save" class="btn">
                <?php echo $this->trans('send'); ?>
            </button>
        </div>
    </div>
</form>

<table class="table table-bordered table-striped table-responsive">
        <?php foreach ($this->get('shoutbox') as $shoutbox): {
                echo '<tr>';         
                echo '<td><b>'.$this->escape($shoutbox->getName()).'</b><br />';
                echo '<span style="font-size:12px">'.$shoutbox->getTime().'</span></td>';  
                echo '</tr>';
                echo '<tr>';
                /*
                 * @todo should fix this regex. 
                 */
                echo '<td>' . preg_replace('/([^\s]{' . $this->get('maxwordlength') . '})(?=[^\s])/', "$1\n", $this->escape($shoutbox->getTextarea())) . '</td>';  
                echo '</tr>';
            }
        endforeach; ?>
</table>

<div align="center"><a href="<?php echo $this->url(array('module' => 'shoutbox', 'controller' => 'index', 'action' => 'index')); ?>"><?php echo $this->trans('archive'); ?></a></div>