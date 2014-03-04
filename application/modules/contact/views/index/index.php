<form method="POST" action="<?php echo $this->getUrl(array('action' => $this->getRequest()->getActionName())); ?>">
    <?php echo $this->getTokenField(); ?>
    <div class="ilch_form_group">
        <label for="receiver">
            <?php echo $this->getTrans('receiver'); ?>:
        </label>
        <div class="controls">
            <select class="ilch_cbo"
                    id="receiver"
                    name="receiver">
                <?php
                    foreach($this->get('receivers') as $receiver)
                    {
                        echo '<option value="'.$receiver->getId().'">'.$this->escape($receiver->getName()).'</option>';
                    }
                ?>
            </select>
        </div>
    </div>
    <div class="ilch_form_group">
        <label for="name">
            <?php echo $this->getTrans('name'); ?>:
        </label>
        <div class="controls">
            <input class="ilch_txt"
                   id="name"
                   name="name"
                   type="text"
                   value="" />
        </div>
    </div>
    <div class="ilch_form_group">
        <label for="email">
            <?php echo $this->getTrans('email'); ?>:
        </label>
        <div class="controls">
            <input class="ilch_txt"
                   id="email"
                   name="email"
                   type="text"
                   value="" />
        </div>
    </div>
    <div class="ilch_form_group">
        <label for="message">
            <?php echo $this->getTrans('message'); ?>:
        </label>
        <div class="controls">
            <textarea class="ilch_txt"
                   id="message"
                   name="message"></textarea>
        </div>
    </div>
    <div class="ilch_form_group">
        <div class="controls">
            <button type="submit" name="save" class="ilch_btn">
                <?php echo $this->getTrans('send'); ?>
            </button>
        </div>
    </div>
</form>