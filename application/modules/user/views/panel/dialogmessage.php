<?php if ($this->get('inbox') != ''): ?>
    <?php foreach ($this->get('inbox') as $inbox): ?>
        <?php $date = new \Ilch\Date($inbox->getTime()); ?>
        <li class="<?=$inbox->getId() == ($this->getUser()->getId()) ? 'right' : 'left'; ?>">
            <div class="body">
                <div data-crid="<?=$inbox->getCrId() ?>" class="message well well-sm">
                    <?=nl2br($this->getHtmlFromBBCode($this->escape($inbox->getText()))) ?>
                </div>
            </div>
            <small class="timestamp">
                <?php
                if (strtotime($date) <= strtotime('-7 day')) {
                    echo $date->format('d.m.Y H:i', true);
                } elseif (strtotime($date) <= strtotime('-2 day') AND strtotime($date) >= strtotime('-6 day') ) {
                    echo $this->getTrans($date->format('l', true)).$date->format(' H.i', true);
                } elseif (strtotime($date) <= strtotime('-1 day')) {
                    echo $this->getTrans('profileYesterday').' '.$date->format('H:i', true);
                } else {
                    echo $date->format('H:i', true);
                };
                ?>
            </small>
        </li>
    <?php endforeach; ?>
<?php endif; ?>

<script>
    $(".message").click(function() {
        let menu = '<div class="menu" data-crid="'+$(this).attr("data-crid")+'">delete</div>';
        let sel = getSelection().toString();

        $("#menucode").html(menu);
        $(".menu").dialog({
            autoOpen: false,
            appendTo: "#menucode",
            dialogClass: "no-close no-titlebar",
            minHeight: 10,
            minWidth: 100,
            width: "auto",
            position: {of: $(this)}
        });

        if (!sel && !$(this).parent().closest('li').hasClass("left")) {
            $(".menu").dialog("open");
        }
    });

    $("#menucode").click(function(e) {
        let menuSelector = $(".menu");
        let crid = menuSelector.attr("data-crid");

        $.post('<?=$this->getUrl('user/panel/deletedialogmessage/id/') ?>'+crid);
        menuSelector.dialog("close");
        $("#menucode").html('');
    });

    $(document).mouseup(function(e) {
        let myDialog = $(".menu");
        let container = $(".ui-dialog");

        if (myDialog.dialog("isOpen") === true) {
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                myDialog.dialog("close");
            }
        }
    });
</script>
