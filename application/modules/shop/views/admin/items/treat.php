<?php
$shopImgPath = '/application/modules/shop/static/img/';
?>

<link href="<?=$this->getModuleUrl('static/css/shop_admin.css') ?>" rel="stylesheet">

<h1>
    <?=(!empty($this->get('shopItem'))) ? $this->getTrans('edit') : $this->getTrans('add'); ?>
</h1>

<?php if ($this->get('cats') != '') : ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>

        <div class="row mb-3 <?=$this->validation()->hasError('status') ? 'has-error' : '' ?>">
            <label for="status" class="col-lg-2 control-label">
                <?=$this->getTrans('visibility') ?>
            </label>
            <div class="col-lg-4">
                <div class="flipswitch">
                    <input type="radio" class="flipswitch-input" id="status-on" name="status" value="1" <?php if ($this->get('shopItem') && $this->get('shopItem')->getStatus() == '1') { echo 'checked="checked"'; } ?> />
                    <label for="status-on" class="flipswitch-label flipswitch-label-on"><i class="fa-solid fa-eye"></i> <?=$this->getTrans('on') ?></label>
                    <input type="radio" class="flipswitch-input" id="status-off" name="status" value="0" <?php if (empty($this->get('shopItem')) || $this->get('shopItem')->getStatus() != '1') { echo 'checked="checked"'; } ?> />
                    <label for="status-off" class="flipswitch-label flipswitch-label-off"><i class="fa-solid fa-eye-slash"></i> <?=$this->getTrans('off') ?></label>
                    <span class="flipswitch-selection"></span>
                </div>
            </div>
        </div>

        <div class="row mb-3 <?=$this->validation()->hasError('catId') ? 'has-error' : '' ?>">
            <label for="catId" class="col-lg-2 control-label">
                <?=$this->getTrans('cat') ?>:
            </label>
            <div class="col-lg-5">
                <select class="form-control" id="catId" name="catId">
                    <?php
                    foreach ($this->get('cats') as $model) {
                        $selected = '';

                        if ($this->get('shopItem') != '' && $this->get('shopItem')->getCatId() == $model->getId()) {
                            $selected = 'selected="selected"';
                        } elseif ($this->getRequest()->getParam('catId') != '' && $this->getRequest()->getParam('catId') == $model->getId()) {
                            $selected = 'selected="selected"';
                        }

                        echo '<option ' . $selected . ' value="' . $model->getId() . '">' . $this->escape($model->getTitle()) . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="row mb-3 <?=$this->validation()->hasError('name') ? 'has-error' : '' ?>">
            <label for="name" class="col-lg-2 control-label">
                <?=$this->getTrans('productName') ?>:
            </label>
            <div class="col-lg-5">
                <input type="text"
                       class="form-control"
                       id="name"
                       name="name"
                       value="<?=($this->get('shopItem') != '') ? $this->escape($this->get('shopItem')->getName()) : $this->escape($this->originalInput('name')) ?>" />
            </div>
        </div>

        <div class="row mb-3 <?=$this->validation()->hasError('itemnumber') ? 'has-error' : '' ?>">
            <label for="itemnumber" class="col-lg-2 control-label">
                <?=$this->getTrans('itemNumber') ?>:
            </label>
            <div class="col-lg-5">
                <input type="text"
                       class="form-control"
                       id="itemnumber"
                       name="itemnumber"
                       value="<?=($this->get('shopItem') != '') ? $this->escape($this->get('shopItem')->getItemnumber()) : $this->escape($this->originalInput('itemnumber')) ?>" />
            </div>
        </div>

        <div class="row mb-3 <?=$this->validation()->hasError('stock') ? 'has-error' : '' ?>">
            <label for="stock" class="col-lg-2 control-label">
                <?=$this->getTrans('stock') ?> / <?=$this->getTrans('salesUnit') ?>:
            </label>
            <div class="col-lg-5 input-group">
                <div class="mb-3">
                    <input type="number"
                           class="form-control"
                           id="stock"
                           name="stock"
                           min="0"
                           value="<?=($this->get('shopItem') != '') ? $this->escape($this->get('shopItem')->getStock()) : $this->escape($this->originalInput('stock')) ?>" />
                </div>
                <div class="mb-3">
                    <input type="text"
                           class="form-control"
                           id="unitName"
                           name="unitName"
                           placeholder="<?=$this->getTrans('piece') ?>"
                           value="<?=($this->get('shopItem') != '') ? $this->escape($this->get('shopItem')->getUnitName()) : $this->escape($this->originalInput('unitName')) ?>" />
                </div>
            </div>
        </div>

        <div class="row mb-3 <?=$this->validation()->hasError('cordon') || $this->validation()->hasError('cordonColor') ? 'has-error' : '' ?>">
            <label for="cordon" class="col-lg-2 control-label">
                <?=$this->getTrans('cordon') ?>:
            </label>
            <div class="col-lg-5">
                <div class="input-group">
                    <div class="flipswitch cordon-flipswitch">
                        <input type="radio" class="flipswitch-input" id="cordon-on" name="cordon" value="1" <?php if ($this->get('shopItem') && $this->get('shopItem')->getCordon() == '1') { echo 'checked="checked"'; } ?> />
                        <label for="cordon-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                        <input type="radio" class="flipswitch-input" id="cordon-off" name="cordon" value="0" <?php if (empty($this->get('shopItem')) || $this->get('shopItem')->getCordon() != '1') { echo 'checked="checked"'; } ?> />
                        <label for="cordon-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                        <span class="flipswitch-selection"></span>
                    </div>
                    <input type="text"
                           class="form-control cordon-text <?=($this->get('shopItem') != '' && $this->get('shopItem')->getCordon() == 1 && $this->get('shopItem')->getCordonColor()) ? $this->get('shopItem')->getCordonColor() : '' ?>"
                           id="cordonText"
                           name="cordonText"
                           maxlength="10"
                           value="<?=($this->get('shopItem') != '') ? $this->escape($this->get('shopItem')->getCordonText()) : $this->escape($this->originalInput('cordonText')) ?>" />
                    <select class="form-control selectpicker cordon-color" id="cordonColor" name="cordonColor">
                        <option selected disabled><?=$this->getTrans('chooseColor') ?></option>
                        <option value="grey" class="grey" <?=($this->get('shopItem') != '' && $this->get('shopItem')->getCordonColor() == 'grey') ? 'selected="selected"' : ''; ?>><?=$this->getTrans('grey') ?></option>
                        <option value="green" class="green" <?=($this->get('shopItem') != '' && $this->get('shopItem')->getCordonColor() == 'green') ? 'selected="selected"' : ''; ?>><?=$this->getTrans('green') ?></option>
                        <option value="yellow" class="yellow" <?=($this->get('shopItem') != '' && $this->get('shopItem')->getCordonColor() == 'yellow') ? 'selected="selected"' : ''; ?>><?=$this->getTrans('yellow') ?></option>
                        <option value="red" class="red" <?=($this->get('shopItem') != '' && $this->get('shopItem')->getCordonColor() == 'red') ? 'selected="selected"' : ''; ?>><?=$this->getTrans('red') ?></option>
                        <option value="blue" class="blue" <?=($this->get('shopItem') != '' && $this->get('shopItem')->getCordonColor() == 'blue') ? 'selected="selected"' : ''; ?>><?=$this->getTrans('blue') ?></option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row mb-3 <?=$this->validation()->hasError('price') ? 'has-error' : '' ?>">
            <label for="price" class="col-lg-2 control-label">
                <?=$this->getTrans('price') ?>:
            </label>
            <div class="col-lg-5">
                <div class="input-group">
                    <span class="input-group-text">
                        <span class="fa-solid fa-info" data-toggle="event-popover" title="<?=$this->getTrans('popoverInfo') ?>" data-content="<?=$this->getTrans('priceInfo') ?>"></span>
                    </span>
                    <input type="text"
                           class="form-control text-right"
                           id="price"
                           name="price"
                           pattern="^\d*(\.\d{2}$)?"
                           placeholder="99.00"
                           value="<?=($this->get('shopItem') != '') ? $this->escape($this->get('shopItem')->getPrice()) : $this->escape($this->originalInput('price')) ?>" />
                    <span class="input-group-text">
                        <b><?=$this->escape($this->get('currency')) ?></b>
                    </span>
                </div>
            </div>
        </div>

        <div class="row mb-3<?=$this->validation()->hasError('tax') ? 'has-error' : '' ?>">
            <label for="tax" class="col-lg-2 control-label">
                <?=$this->getTrans('tax') ?>:
            </label>
            <div class="col-lg-5">
                <div class="input-group">
                    <input type="number"
                           class="form-control"
                           id="tax"
                           name="tax"
                           min="1"
                           placeholder="<?=($this->escape($this->get('settings')->getFixTax()) != '') ? $this->escape($this->get('settings')->getFixTax()) : '' ?>"
                           value="<?php if ($this->get('shopItem') != '') {
                                      echo $this->escape($this->get('shopItem')->getTax());
                                  } elseif ($this->escape($this->originalInput('tax'))) {
                                      echo $this->escape($this->originalInput('tax'));
                                  } else {
                                      echo $this->escape($this->get('settings')->getFixTax());
                                  } ?>" />
                    <span class="input-group-text">
                        <b><?=$this->getTrans('percent') ?> (%)</b>
                    </span>
                </div>
            </div>
        </div>

        <div class="row mb-3 <?=$this->validation()->hasError('shippingCosts') ? 'has-error' : '' ?>">
            <label for="shippingCosts" class="col-lg-2 control-label">
                <?=$this->getTrans('shippingCosts') ?>:
            </label>
            <div class="col-lg-5">
                <div class="input-group">
                    <span class="input-group-text">
                        <span class="fa-solid fa-info" data-toggle="event-popover" title="<?=$this->getTrans('popoverInfo') ?>" data-content="<?=$this->getTrans('priceInfo') ?>"></span>
                    </span>
                    <input type="text"
                           class="form-control text-right"
                           id="shippingCosts"
                           name="shippingCosts"
                           pattern="^\d*(\.\d{2}$)?"
                           placeholder="<?=($this->escape($this->get('settings')->getFixShippingCosts()) != '') ? $this->escape($this->get('settings')->getFixShippingCosts()) : '' ?>"
                           value="<?php if ($this->get('shopItem') != '') {
                                      echo $this->escape($this->get('shopItem')->getShippingCosts());
                                  } elseif ($this->escape($this->originalInput('shippingCosts'))) {
                                      echo $this->escape($this->originalInput('shippingCosts'));
                                  } else {
                                      echo $this->escape($this->get('settings')->getFixShippingCosts());
                                  } ?>" />
                    <span class="input-group-text">
                        <b><?=$this->escape($this->get('currency')) ?></b>
                    </span>
                </div>
            </div>
        </div>

        <div class="row mb-3 <?=$this->validation()->hasError('shippingTime') ? 'has-error' : '' ?>">
            <label for="shippingTime" class="col-lg-2 control-label">
                <?=$this->getTrans('shippingTime') ?>:
            </label>
            <div class="col-lg-5">
                <div class="input-group">
                    <input type="number"
                           class="form-control"
                           id="shippingTime"
                           name="shippingTime"
                           min="1"
                           placeholder="<?=($this->escape($this->get('settings')->getFixShippingTime()) != '') ? $this->escape($this->get('settings')->getFixShippingTime()) : '' ?>"
                           value="<?php if ($this->get('shopItem') != '') {
                                      echo $this->escape($this->get('shopItem')->getShippingTime());
                                  } elseif ($this->escape($this->originalInput('shippingTime'))) {
                                      echo $this->escape($this->originalInput('shippingTime'));
                                  } else {
                                      echo $this->escape($this->get('settings')->getFixShippingTime());
                                  } ?>" />
                    <span class="input-group-text">
                        <b><?=$this->getTrans('days') ?></b>
                    </span>
                </div>
            </div>
        </div>

        <div class="row mb-3 <?=$this->validation()->hasError('image') ? 'has-error' : '' ?>">
            <label for="selectedImage_image" class="col-lg-2 control-label">
                <?=$this->getTrans('productThumbnail') ?>:
            </label>
            <div class="col-lg-5">
                <div class="input-group">
                    <span class="input-group-text">
                        <?php
                        if ($this->get('shopItem') != '' && $this->get('shopItem')->getImage() && file_exists(ROOT_PATH . '/' . $this->get('shopItem')->getImage())) {
                            $img = BASE_URL . '/' . $this->get('shopItem')->getImage();
                        } else {
                            $img = BASE_URL . $shopImgPath . 'noimg.jpg';
                        } ?>
                        <span class="fa-solid fa-eye" data-toggle="event-image" data-img="<?=$img ?>"></span>
                    </span>
                    <input type="text"
                           class="form-control"
                           id="selectedImage_image"
                           name="image"
                           placeholder="<?=$this->getTrans('choosePic') ?>"
                           value="<?=($this->get('shopItem') != '') ? $this->escape($this->get('shopItem')->getImage()) : $this->escape($this->originalInput('image')) ?>" />
                    <span class="input-group-text">
                        <span class="fa-solid fa-xmark clearImage"></span>
                    </span>
                    <span class="input-group-text">
                        <a id="media_image" href="javascript:media_image()"><i class="fa-regular fa-image"></i></a>
                        <script>
                            <?=$this->getMedia()
                                ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/input/_image/'))
                                ->addInputId('_image')
                                ->addUploadController($this->getUrl('admin/media/index/upload')) ?>
                        </script>
                    </span>
                </div>
            </div>
        </div>

        <div class="row mb-3 <?=$this->validation()->hasError('image1') ? 'has-error' : '' ?>">
            <label for="selectedImage_image1" class="col-lg-2 control-label">
                <?=$this->getTrans('productImage') ?> 1:
            </label>
            <div class="col-lg-5">
                <div class="input-group">
                    <span class="input-group-text">
                        <?php
                        if ($this->get('shopItem') != '' && $this->get('shopItem')->getImage1() && file_exists(ROOT_PATH . '/' . $this->get('shopItem')->getImage1())) {
                            $img1 = BASE_URL . '/' . $this->get('shopItem')->getImage1();
                        } else {
                            $img1 = BASE_URL . $shopImgPath . 'noimg.jpg';
                        } ?>
                        <span class="fa-solid fa-eye" data-toggle="event-image" data-img="<?=$img1 ?>"></span>
                    </span>
                    <input type="text"
                           class="form-control"
                           id="selectedImage_image1"
                           name="image1"
                           placeholder="<?=$this->getTrans('choosePic') ?>"
                           value="<?=($this->get('shopItem') != '') ? $this->escape($this->get('shopItem')->getImage1()) : $this->escape($this->originalInput('image1')) ?>" />
                    <span class="input-group-text">
                        <span class="fa-solid fa-xmark clearImage"></span>
                    </span>
                    <span class="input-group-text">
                        <a id="media_image1" href="javascript:media_image1()"><i class="fa-regular fa-image"></i></a>
                        <script>
                            <?=$this->getMedia()
                                ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/input/_image1/'))
                                ->addInputId('_image1')
                                ->addUploadController($this->getUrl('admin/media/index/upload')) ?>
                        </script>
                    </span>
                </div>
            </div>
        </div>

        <div class="row mb-3 <?=$this->validation()->hasError('image2') ? 'has-error' : '' ?>">
            <label for="selectedImage_image2" class="col-lg-2 control-label">
                <?=$this->getTrans('productImage') ?> 2:
            </label>
            <div class="col-lg-5">
                <div class="input-group">
                    <span class="input-group-text">
                        <?php
                        if ($this->get('shopItem') != '' && $this->get('shopItem')->getImage2() && file_exists(ROOT_PATH . '/' . $this->get('shopItem')->getImage2())) {
                            $img2 = BASE_URL . '/' . $this->get('shopItem')->getImage2();
                        } else {
                            $img2 = BASE_URL . $shopImgPath . 'noimg.jpg';
                        } ?>
                        <span class="fa-solid fa-eye" data-toggle="event-image" data-img="<?=$img2 ?>"></span>
                    </span>
                    <input type="text"
                           class="form-control"
                           id="selectedImage_image2"
                           name="image2"
                           placeholder="<?=$this->getTrans('choosePic') ?>"
                           value="<?=($this->get('shopItem') != '') ? $this->escape($this->get('shopItem')->getImage2()) : $this->escape($this->originalInput('image2')) ?>" />
                    <span class="input-group-text">
                        <span class="fa-solid fa-xmark clearImage"></span>
                    </span>
                    <span class="input-group-text">
                        <a id="media_image2" href="javascript:media_image2()"><i class="fa-regular fa-image"></i></a>
                        <script>
                            <?=$this->getMedia()
                                ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/input/_image2/'))
                                ->addInputId('_image2')
                                ->addUploadController($this->getUrl('admin/media/index/upload')) ?>
                        </script>
                    </span>
                </div>
            </div>
        </div>

        <div class="row mb-3 <?=$this->validation()->hasError('image3') ? 'has-error' : '' ?>">
            <label for="selectedImage_image3" class="col-lg-2 control-label">
                <?=$this->getTrans('productImage') ?> 3:
            </label>
            <div class="col-lg-5">
                <div class="input-group">
                    <span class="input-group-text">
                        <?php
                        if ($this->get('shopItem') != '' && $this->get('shopItem')->getImage3() && file_exists(ROOT_PATH . '/' . $this->get('shopItem')->getImage3())) {
                            $img3 = BASE_URL . '/' . $this->get('shopItem')->getImage3();
                        } else {
                            $img3 = BASE_URL . $shopImgPath . 'noimg.jpg';
                        } ?>
                        <span class="fa-solid fa-eye" data-toggle="event-image" data-img="<?=$img3 ?>"></span>
                    </span>
                    <input type="text"
                           class="form-control"
                           id="selectedImage_image3"
                           name="image3"
                           placeholder="<?=$this->getTrans('choosePic') ?>"
                           value="<?=($this->get('shopItem') != '') ? $this->escape($this->get('shopItem')->getImage3()) : $this->escape($this->originalInput('image3')) ?>" />
                    <span class="input-group-text">
                        <span class="fa-solid fa-xmark clearImage"></span>
                    </span>
                    <span class="input-group-text">
                        <a id="media_image3" href="javascript:media_image3()"><i class="fa-regular fa-image"></i></a>
                        <script>
                            <?=$this->getMedia()
                                ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/input/_image3/'))
                                ->addInputId('_image3')
                                ->addUploadController($this->getUrl('admin/media/index/upload')) ?>
                        </script>
                    </span>
                </div>
            </div>
        </div>

        <div class="row mb-3 <?=$this->validation()->hasError('info') ? 'has-error' : '' ?>">
            <label for="info" class="col-lg-2 control-label">
                <?=$this->getTrans('shortInfo') ?>:
            </label>
            <div class="col-lg-10">
                <textarea class="form-control ckeditor"
                          id="info"
                          name="info"
                          cols="50"
                          rows="3"
                          toolbar="ilch_html">
                          <?=($this->get('shopItem') != '') ? $this->escape($this->get('shopItem')->getInfo()) : $this->escape($this->originalInput('info')) ?>
                </textarea>
            </div>
        </div>

        <div class="row mb-3 <?=$this->validation()->hasError('desc') ? 'has-error' : '' ?>">
            <label for="desc" class="col-lg-2 control-label">
                <?=$this->getTrans('description') ?>:
            </label>
            <div class="col-lg-10">
                <textarea class="form-control ckeditor"
                          id="desc"
                          name="desc"
                          cols="50"
                          rows="3"
                          toolbar="ilch_html">
                          <?=($this->get('shopItem') != '') ? $this->escape($this->get('shopItem')->getDesc()) : $this->escape($this->originalInput('desc')) ?>
                </textarea>
            </div>
        </div>

        <?=(!empty($this->get('shopItem'))) ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton'); ?>
    </form>
<?php else : ?>
    <?=$this->getTrans('noCategory') ?>
<?php endif; ?>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<script>
$(function () {
    $('[data-toggle="event-popover"]').popover({
        container: 'body',
        trigger: 'hover',
        placement: 'top',
    });
    $('[data-toggle="event-image"]').popover({
        html: true,
        trigger: 'hover',
        placement: 'top',
        content: function () { return '<img src="' + $(this).data('img') + '" width="200" />'; }
    });
    $("span .clearImage").click(function(){
        $(this).parent().siblings('input[type="text"]').val('');
    });
});
</script>
