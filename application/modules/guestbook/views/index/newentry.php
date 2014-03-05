<form action="" method="POST">
    <?php echo $this->getTokenField(); ?>
    <div class="ilch_form_group">
        <label>
            <?=$this->getTrans('name'); ?>*
        </label>
        <div class="controls">
            <input type="text"
                   name="name"
                   placeholder="Name"
                   required />
        </div>
    </div>
    <div class="ilch_form_group">
        <label>
            <?=$this->getTrans('email'); ?>*
        </label>
        <div class="controls">
            <input type="text"
                   name="email" 
                   placeholder="E-Mail" />
        </div>
    </div>
    <div class="ilch_form_group">
        <label>
            <?=$this->getTrans('page'); ?>
        </label>
        <div class="controls">
           <input type="text"
                  name="homepage" 
                  placeholder="<?php echo $this->getTrans('page'); ?>" />
        </div>
    </div>
    <div class="ilch_form_group">
        <label>
            <?=$this->getTrans('message'); ?>*
        </label>
        <div class="controls">
            <textarea id="ilch_bbcode"
                      name="text"
                      required>
            </textarea>
        </div>
    </div>
    <div class="ilch_form_group">
        <input type="submit" 
               name="saveEntry" 
               class="ilch_pull_right" 
               value="<?php echo $this->getTrans('submit'); ?>" />
    </div>
</form>

