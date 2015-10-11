<script type="text/javascript" >
    $(function() {
        var $shoutboxContainer = $('.shoutbox-container');

        $shoutboxContainer.on('click', 'button[type=submit]', function(ev) {
            ev.preventDefault();
            var $btn = $(this),
                $form = $btn.closest('form'),
                dataString = $form.serialize();

            if ($form.find('[name=shoutbox_name]').val() == '' || $form.find('[name=shoutbox_textarea]').val() == '') {
                alert("Please Enter Some Text");
            } else {
                $.ajax({
                    type: "POST",
                    url: "<?=$this->getUrl('shoutbox/index/ajax')?>",
                    data: dataString,
                    cache: false,
                    success: function(html){
                        var $htmlWithoutScript = $(html).filter('.shoutbox-container');
                        $form.closest('.shoutbox-container').html($htmlWithoutScript.html());
                    }
                });
            }
        });
    });
</script>
<div class="shoutbox-container">
<form class="form-horizontal" action="" method="post">
   <?=$this->getTokenField() ?>
    <div class="form-group hidden">
        <label class="col-lg-2 control-label">
            <?=$this->getTrans('bot') ?>
        </label>
        <div class="col-lg-8">
            <input type="text"
                   class="form-control"
                   name="bot"
                   placeholder="Bot" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-12">
            <input class="form-control"
                   id="name"
                   name="shoutbox_name"
                   type="text"
                   placeholder="Name"
                   value="<?php if($this->getUser() !== null) { echo $this->escape($this->getUser()->getName());} ?>"
                   required />
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-12">
            <textarea class="form-control"
                      name="shoutbox_textarea" 
                      id="x"
                      cols="10" 
                      rows="5"
                      maxlength="50"
                      placeholder="<?=$this->getTrans('message') ?>"
                      required></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-12">
            <button type="submit" value="1" name="form_<?=$this->get('uniqid') ?>" class="btn">
                <?=$this->getTrans('send') ?>
            </button>
        </div>
    </div>
</form>

<?php if ($this->get('shoutbox') != ''): ?>
    <table class="table table-bordered table-striped table-responsive">
        <?php foreach ($this->get('shoutbox') as $shoutbox): ?>
            <?php $userMapper = new \Modules\User\Mappers\User() ?>
            <?php $user = $userMapper->getUserById($shoutbox->getUid()) ?>
            <?php $date = new \Ilch\Date($shoutbox->getTime()) ?>
            <tr>
                <?php if ($shoutbox->getUid() == '0'): ?>
                    <td>
                        <b><?=$this->escape($shoutbox->getName()) ?>:</b><br />
                        <span class="small"><?=$date->format("d.m.Y H:i", true) ?></span>
                    </td>
                <?php else: ?>
                    <td>
                        <b><a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>"><?=$user->getName() ?></a>:</b><br />
                        <span class="small"><?=$date->format("d.m.Y H:i", true) ?></span>
                    </td>
                <?php endif; ?>
            </tr>
            <tr>
                <?php
                /*
                 * @todo should fix this regex.
                 */
                ?>
                <td><?=preg_replace('/([^\s]{' . $this->get('maxwordlength') . '})(?=[^\s])/', "$1\n", $this->escape($shoutbox->getTextarea())) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<div align="center"><a href="<?=$this->getUrl('shoutbox/index/index/') ?>"><?=$this->getTrans('archive') ?></a></div>
</div>
