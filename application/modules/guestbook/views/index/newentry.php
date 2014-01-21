<legend><?php echo $this->getTrans('entry'); ?></legend>
<p class="pull-right">
    <a href="<?php echo $this->getUrl(array('action' => 'index')); ?>"
       class="btn btn-small btn-primary"
       type="button">
        <?php echo $this->getTrans('back'); ?>
    </a>
</p>
<div class="form-group">
    <form class="navbar-form navbar-left" 
          action="<?php $this->getUrl(array('action' => 'newEntry')); ?>" 
          method="post">
        <?php echo $this->getTokenField(); ?>
        <p>
            <?php echo $this->getTrans('name'); ?>*<br />
            <input type="text" 
                   name="name" 
                   class="form-control" 
                   placeholder="Name"
                   required />
        </p>
        <p>
            <?php echo $this->getTrans('email'); ?><br />
            <input type="text" 
                   name="email" 
                   class="form-control" 
                   placeholder="E-Mail" />
        </p>
        <p>
            <?php echo $this->getTrans('page'); ?><br />
            <input type="text" 
                   name="homepage" 
                   class="form-control" 
                   placeholder="<?php echo $this->getTrans('page'); ?>" />
        </p>
        <p>
            <?php echo $this->getTrans('message'); ?>*<br />
            <textarea id="guestbook_message"
                      name="text" 
                      cols="40" 
                      rows="5"
                      required>
            </textarea>
        </p>
        <p>
            <input type="submit" 
                   name="saveEntry" 
                   class="btn btn-small btn-primary" 
                   value="<?php echo $this->getTrans('submit'); ?>" />
        </p>
    </form>
</div>

<script type="text/javascript" src="<?php echo $this->getStaticUrl('js/tinymce/tinymce.min.js') ?>"></script>
<script>
    tinymce.init
    (
        {
            height: 400,
            selector: "#guestbook_message",
            plugins: "image preview table"
        }
    );
</script>

