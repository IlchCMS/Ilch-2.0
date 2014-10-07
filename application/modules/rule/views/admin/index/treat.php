<form class="form-horizontal" method="POST" action="<?php echo $this->getUrl(array('action' => $this->getRequest()->getActionName(), 'id' => $this->getRequest()->getParam('id'))); ?>">
    <?php echo $this->getTokenField(); ?>
    <legend>
    <?php
        if ($this->get('rule') != '') {
            echo $this->getTrans('menuActionEditRule');
        } else {
            echo $this->getTrans('menuActionNewRule');
        }
    ?>
    </legend>
    <div class="form-group">
        <label for="paragraph" class="col-lg-2 control-label">
            <?php echo $this->getTrans('paragraph'); ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="number"
                   min="1"
                   name="paragraph"
                   id="paragraph"
                   placeholder="Paragraph"
                   value="<?php if ($this->get('rule') != '') { echo $this->escape($this->get('rule')->getParagraph()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="title" class="col-lg-2 control-label">
            <?php echo $this->getTrans('title'); ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="title"
                   id="title"
                   placeholder="Title"
                   value="<?php if ($this->get('rule') != '') { echo $this->escape($this->get('rule')->getTitle()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="text" class="col-lg-2 control-label">
            <?php echo $this->getTrans('text'); ?>:
        </label>
        <div class="col-lg-10">
            <textarea class="form-control"
                   name="text" 
                   id="ilch_bbcode"
                   rows="5"><?php if ($this->get('rule') != '') { echo $this->escape($this->get('rule')->getText()); } ?></textarea>
        </div>
    </div>
    <?php
    if ($this->get('rule') != '') {
        echo $this->getSaveBar('updateButton');
    } else {
        echo $this->getSaveBar('addButton');
    }
    ?>
</form>

<script>
    $("input[name='date']").TouchSpin({
      verticalbuttons: true
    });
</script>