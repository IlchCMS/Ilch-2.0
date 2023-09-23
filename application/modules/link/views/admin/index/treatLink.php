<h1>
    <?php
    if ($this->get('link') != '') {
        echo $this->getTrans('menuActionEditLink');
    } else {
        echo $this->getTrans('menuActionNewLink');
    }
    ?>
</h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row form-group ilch-margin-b <?=$this->validation()->hasError('name') ? 'has-error' : '' ?>">
        <label for="name" class="col-lg-2 control-label">
            <?=$this->getTrans('name') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   placeholder="Name"
                   value="<?=($this->get('link') != '') ? $this->escape($this->get('link')->getName()) : $this->escape($this->get('post')['name']) ?>" />
        </div>
    </div>
    <div class="row form-group ilch-margin-b <?=$this->validation()->hasError('link') ? 'has-error' : '' ?>">
        <label for="link" class="col-lg-2 control-label">
            <?=$this->getTrans('link') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="link"
                   name="link"
                   placeholder="http://"
                   value="<?=($this->get('link') != '') ? $this->escape($this->get('link')->getLink()) : $this->escape($this->get('post')['link']) ?>" />
        </div>
    </div>
    <div class="row form-group ilch-margin-b <?=$this->validation()->hasError('banner') ? 'has-error' : '' ?>">
        <label for="selectedImage_1" class="col-lg-2 control-label">
            <?=$this->getTrans('banner') ?>:
        </label>
        <div class="col-lg-4">
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       id="selectedImage_1"
                       name="banner"
                       placeholder="<?=$this->getTrans('httpOrMedia') ?>"
                       value="<?=($this->get('link') != '') ? $this->escape($this->get('link')->getBanner()) : $this->escape($this->get('post')['banner']) ?>" />
                <span class="input-group-text"><a id="media" href="javascript:media_1()"><i class="fa-regular fa-image"></i></a></span>
            </div>
        </div>
    </div>
    <div class="row form-group ilch-margin-b">
        <label for="desc" class="col-lg-2 control-label">
            <?=$this->getTrans('description') ?>:
        </label>
        <div class="col-lg-4">
            <textarea class="form-control"
                      id="desc"
                      name="desc"
                      cols="45"
                      rows="3"><?=($this->get('link') != '') ? $this->escape($this->get('link')->getDesc()) : $this->escape($this->get('post')['desc']) ?></textarea>
        </div>
    </div>
    <div class="row form-group ilch-margin-b <?=$this->validation()->hasError('catId') ? 'has-error' : '' ?>">
        <label for="catId" class="col-lg-2 control-label">
            <?=$this->getTrans('category') ?>:
        </label>
        <div class="col-lg-4">
            <select class="form-control" id="catId" name="catId">
                <option value="0">-- <?=$this->getTrans('optionNoCategory') ?> --</option>
                <?php
                if ($this->get('cats') != '') {
                    foreach ($this->get('cats') as $model) {
                        $selected = '';
                        if ($this->get('link') != '' && $this->get('link')->getCatId() == $model->getId()) {
                            $selected = 'selected="selected"';
                        }

                        echo '<option '.$selected.' value="'.$model->getId().'">'.$this->escape($model->getName()).'</option>';
                    }
                }
                ?>
            </select>
        </div>
    </div>
    <?=($this->get('link') != '') ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<script>
<?=$this->getMedia()
        ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/input/_1/'))
        ->addInputId('_1')
        ->addUploadController($this->getUrl('admin/media/index/upload'))
?>
</script>
