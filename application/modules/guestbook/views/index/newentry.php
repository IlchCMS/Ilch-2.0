<legend><?php echo $this->trans('entry'); ?></legend>
<p class="pull-right">
    <a href="<?php echo $this->url(array('action' => 'index')); ?>" class="btn btn-small btn-primary" type="button" ><?php echo $this->trans('back'); ?></a>
</p>
<div class="form-group">
    <form class="navbar-form navbar-left" 
          action="<?php $this->url(array('action' => 'newEntry')); ?>" 
          method="post">
              <?php echo $this->getTokenField(); ?>
        <p>
            <?php echo $this->trans('name'); ?><br />
            <input type="text" 
                   name="name" 
                   class="form-control" 
                   placeholder="Name"
                   required />
        </p>
        <p>
            <?php echo $this->trans('email'); ?><br />
            <input type="text" 
                   name="email" 
                   class="form-control" 
                   placeholder="E-Mail"
                   required />
        </p>
        <p>
            <?php echo $this->trans('page'); ?><br />
            <input type="text" 
                   name="homepage" 
                   class="form-control" 
                   placeholder="<?php echo $this->trans('page'); ?>"
                   required />
        </p>

        <p>
            <?php echo $this->trans('message'); ?><br />
            <textarea name="text" 
                      cols="40" 
                      rows="5"
                      required>

            </textarea>
        </p>

        <p>
            <input type="submit" 
                   name="saveEntry" 
                   class="btn btn-small btn-primary" 
                   value="<?php echo $this->trans('submit'); ?>" />
        </p>
    </form>
</div>

<script type="text/javascript" src="<?php echo $this->staticUrl('js/tinymce/tinymce.min.js') ?>"></script>
<script>
    tinymce.init
            (
                    {
                        height: 400,
                        selector: "textarea",
                        plugins: "image preview"

                    }
            );
</script>

