<form method="POST" class="form-horizontal" action="<?php echo $this->getUrl(array('action' => $this->getRequest()->getActionName())); ?>">
    <?php echo $this->getTokenField(); ?>
    <div class="form-group">
        <label for="receiver" class="col-lg-2 control-label">
            <?php echo $this->getTrans('receiver'); ?>:
        </label>
        <div class="col-lg-6">
            <select id="receiver"
                    class="form-control"
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
    <div class="form-group">
        <label for="name" class="col-lg-2 control-label">
            <?php echo $this->getTrans('name'); ?>:
        </label>
        <div class="col-lg-6">
            <input id="name"
                   class="form-control"
                   name="name"
                   type="text"
                   value="" />
        </div>
    </div>
    <div class="form-group">
        <label for="email" class="col-lg-2 control-label">
            <?php echo $this->getTrans('email'); ?>:
        </label>
        <div class="col-lg-6">
            <input id="email"
                   class="form-control"
                   name="email"
                   type="text"
                   value="" />
        </div>
    </div>
    <div class="form-group">
        <label for="message" class="col-lg-2 control-label">
            <?php echo $this->getTrans('message'); ?>:
        </label>
        <div class="col-lg-6">
            <textarea id="message"
                      class="form-control"
                   name="message"></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-6">
            <button type="submit" class="btn" name="save">
                <?php echo $this->getTrans('send'); ?>
            </button>
        </div>
    </div>
</form>