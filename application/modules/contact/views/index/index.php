<form class="form-horizontal" method="POST" action="<?php echo $this->url(array('action' => $this->getRequest()->getActionName())); ?>">
    <?php echo $this->getTokenField(); ?>
    <div class="form-group">
        <label for="receiver" class="col-lg-2 control-label">
            <?php echo $this->trans('receiver'); ?>:
        </label>
        <div class="col-lg-10">
            <select class="form-control"
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
    <div class="form-group">
        <label for="name" class="col-lg-2 control-label">
            <?php echo $this->trans('name'); ?>:
        </label>
        <div class="col-lg-10">
            <input class="form-control"
                   id="name"
                   name="name"
                   type="text"
                   value="" />
        </div>
    </div>
    <div class="form-group">
        <label for="email" class="col-lg-2 control-label">
            <?php echo $this->trans('email'); ?>:
        </label>
        <div class="col-lg-10">
            <input class="form-control"
                   id="email"
                   name="email"
                   type="text"
                   value="" />
        </div>
    </div>
    <div class="form-group">
        <label for="message" class="col-lg-2 control-label">
            <?php echo $this->trans('message'); ?>:
        </label>
        <div class="col-lg-10">
            <textarea class="form-control"
                   id="message"
                   name="message"></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" name="save" class="btn">
                <?php echo $this->trans('send'); ?>
            </button>
        </div>
    </div>
</form>