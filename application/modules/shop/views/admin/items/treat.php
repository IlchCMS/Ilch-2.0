<?php
$shopImgPath = '/application/modules/shop/static/img/';

/** @var \Modules\Shop\Models\Property[] $properties */
$properties = $this->get('properties');

/** @var string[] $propertiesTranslation */
$propertiesTranslation = $this->get('propertiesTranslations');

/** @var \Modules\Shop\Models\Propertyvalue[] $propertiesValues */
$propertiesValues = $this->get('propertiesValues');

/** @var string[] $propertiesValuesTranslations */
$propertiesValuesTranslations = $this->get('propertiesValuesTranslations');

/** @var \Modules\Shop\Models\Propertyvariant[] $propertyVariants */
$propertyVariants = $this->get('propertyVariants');

/** @var \Modules\Shop\Mappers\Category $categoryMapper */
$categoryMapper = $this->get('categoryMapper');

/** @var \Modules\Shop\Models\Item $shopItem */
$shopItem = $this->get('shopItem');
?>

<link href="<?=$this->getModuleUrl('static/css/shop_admin.css') ?>" rel="stylesheet">

<h1>
    <?=(!empty($shopItem)) ? $this->getTrans('edit') : $this->getTrans('add'); ?>
</h1>

<?php if ($this->get('cats') != '') : ?>
    <?=$shopItem && $shopItem->isVariant() ? '<p>' . $this->getTrans('isVariant') . '</p>': '' ?>
    <form method="POST" action="" id="shopItemForm">
        <?=$this->getTokenField() ?>

        <div class="row mb-3<?=$this->validation()->hasError('status') ? ' has-error' : '' ?>">
            <label for="status" class="col-xl-2 col-form-label">
                <?=$this->getTrans('visibility') ?>
            </label>
            <div class="col-xl-4">
                <div class="flipswitch">
                    <input type="radio" class="flipswitch-input" id="status-on" name="status" value="1" <?php if ($shopItem && $shopItem->getStatus() == '1') { echo 'checked="checked"'; } ?> />
                    <label for="status-on" class="flipswitch-label flipswitch-label-on"><i class="fa-solid fa-eye"></i> <?=$this->getTrans('on') ?></label>
                    <input type="radio" class="flipswitch-input" id="status-off" name="status" value="0" <?php if (empty($shopItem) || $shopItem->getStatus() != '1') { echo 'checked="checked"'; } ?> />
                    <label for="status-off" class="flipswitch-label flipswitch-label-off"><i class="fa-solid fa-eye-slash"></i> <?=$this->getTrans('off') ?></label>
                    <span class="flipswitch-selection"></span>
                </div>
            </div>
        </div>

        <div class="row mb-3<?=$this->validation()->hasError('catId') ? ' has-error' : '' ?>">
            <label for="catId" class="col-xl-2 col-form-label">
                <?=$this->getTrans('cat') ?>:
            </label>
            <div class="col-xl-5">
                <select class="form-select" id="catId" name="catId">
                    <?php
                    foreach ($this->get('cats') as $model) {
                        $selected = '';

                        if ($shopItem != '' && $shopItem->getCatId() == $model->getId()) {
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

        <div class="row mb-3<?=$this->validation()->hasError('name') ? ' has-error' : '' ?>">
            <label for="name" class="col-xl-2 col-form-label">
                <?=$this->getTrans('productName') ?>:
            </label>
            <div class="col-xl-5">
                <input type="text"
                       class="form-control"
                       id="name"
                       name="name"
                       value="<?=($shopItem != '') ? $this->escape($shopItem->getName()) : $this->escape($this->originalInput('name')) ?>" />
            </div>
        </div>

        <div class="row mb-3<?=$this->validation()->hasError('itemnumber') ? ' has-error' : '' ?>">
            <label for="itemnumber" class="col-xl-2 col-form-label">
                <?=$this->getTrans('itemNumber') ?>:
            </label>
            <div class="col-xl-5">
                <input type="text"
                       class="form-control"
                       id="itemnumber"
                       name="itemnumber"
                       value="<?=($shopItem != '') ? $this->escape($shopItem->getItemnumber()) : $this->escape($this->originalInput('itemnumber')) ?>" />
            </div>
        </div>

        <div class="row mb-3<?=$this->validation()->hasError('stock') ? ' has-error' : '' ?>">
            <label for="stock" class="col-xl-2 col-form-label">
                <?=$this->getTrans('stock') ?> / <?=$this->getTrans('salesUnit') ?>:
            </label>
            <div class="col-xl-5">
                <div class="input-group">
                    <input type="number"
                           class="form-control"
                           id="stock"
                           name="stock"
                           min="0"
                           value="<?=($shopItem != '') ? $this->escape($shopItem->getStock()) : $this->escape($this->originalInput('stock')) ?>" />
                    <input type="text"
                           class="form-control"
                           id="unitName"
                           name="unitName"
                           placeholder="<?=$this->getTrans('piece') ?>"
                           value="<?=($shopItem != '') ? $this->escape($shopItem->getUnitName()) : $this->escape($this->originalInput('unitName')) ?>" />
                </div>
            </div>
        </div>

        <div class="row mb-3<?=$this->validation()->hasError('cordon') || $this->validation()->hasError('cordonColor') ? ' has-error' : '' ?>">
            <label for="cordon" class="col-xl-2 col-form-label">
                <?=$this->getTrans('cordon') ?>:
            </label>
            <div class="col-xl-5">
                <div class="input-group">
                    <div class="flipswitch cordon-flipswitch">
                        <input type="radio" class="flipswitch-input" id="cordon-on" name="cordon" value="1" <?php if ($shopItem && $shopItem->getCordon() == '1') { echo 'checked="checked"'; } ?> />
                        <label for="cordon-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                        <input type="radio" class="flipswitch-input" id="cordon-off" name="cordon" value="0" <?php if (empty($shopItem) || $shopItem->getCordon() != '1') { echo 'checked="checked"'; } ?> />
                        <label for="cordon-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                        <span class="flipswitch-selection"></span>
                    </div>
                    <input type="text"
                           class="form-control cordon-text <?=($shopItem != '' && $shopItem->getCordon() == 1 && $shopItem->getCordonColor()) ? $shopItem->getCordonColor() : '' ?>"
                           id="cordonText"
                           name="cordonText"
                           maxlength="10"
                           value="<?=($shopItem != '') ? $this->escape($shopItem->getCordonText()) : $this->escape($this->originalInput('cordonText')) ?>" />
                    <select class="form-select selectpicker cordon-color" id="cordonColor" name="cordonColor">
                        <option selected disabled><?=$this->getTrans('chooseColor') ?></option>
                        <option value="grey" class="grey" <?=($shopItem != '' && $shopItem->getCordonColor() == 'grey') ? 'selected="selected"' : ''; ?>><?=$this->getTrans('grey') ?></option>
                        <option value="green" class="green" <?=($shopItem != '' && $shopItem->getCordonColor() == 'green') ? 'selected="selected"' : ''; ?>><?=$this->getTrans('green') ?></option>
                        <option value="yellow" class="yellow" <?=($shopItem != '' && $shopItem->getCordonColor() == 'yellow') ? 'selected="selected"' : ''; ?>><?=$this->getTrans('yellow') ?></option>
                        <option value="red" class="red" <?=($shopItem != '' && $shopItem->getCordonColor() == 'red') ? 'selected="selected"' : ''; ?>><?=$this->getTrans('red') ?></option>
                        <option value="blue" class="blue" <?=($shopItem != '' && $shopItem->getCordonColor() == 'blue') ? 'selected="selected"' : ''; ?>><?=$this->getTrans('blue') ?></option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row mb-3<?=$this->validation()->hasError('price') ? ' has-error' : '' ?>">
            <label for="price" class="col-xl-2 col-form-label">
                <?=$this->getTrans('price') ?>:
            </label>
            <div class="col-xl-5">
                <div class="input-group">
                    <span class="input-group-text">
                        <span class="fa-solid fa-info" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-title="<?=$this->getTrans('priceInfo') ?>"></span>
                    </span>
                    <input type="text"
                           class="form-control text-end"
                           id="price"
                           name="price"
                           pattern="^\d*(\.\d{2}$)?"
                           placeholder="99.00"
                           value="<?=($shopItem != '') ? $this->escape($shopItem->getPrice()) : $this->escape($this->originalInput('price')) ?>" />
                    <span class="input-group-text">
                        <b><?=$this->escape($this->get('currency')) ?></b>
                    </span>
                </div>
            </div>
        </div>

        <div class="row mb-3<?=$this->validation()->hasError('tax') ? ' has-error' : '' ?>">
            <label for="tax" class="col-xl-2 col-form-label">
                <?=$this->getTrans('tax') ?>:
            </label>
            <div class="col-xl-5">
                <div class="input-group">
                    <input type="number"
                           class="form-control"
                           id="tax"
                           name="tax"
                           min="1"
                           placeholder="<?=($this->escape($this->get('settings')->getFixTax()) != '') ? $this->escape($this->get('settings')->getFixTax()) : '' ?>"
                           value="<?php if ($shopItem != '') {
                                      echo $this->escape($shopItem->getTax());
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

        <div class="row mb-3<?=$this->validation()->hasError('shippingCosts') ? ' has-error' : '' ?>">
            <label for="shippingCosts" class="col-xl-2 col-form-label">
                <?=$this->getTrans('shippingCosts') ?>:
            </label>
            <div class="col-xl-5">
                <div class="input-group">
                    <span class="input-group-text">
                        <span class="fa-solid fa-info" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-title="<?=$this->getTrans('priceInfo') ?>"></span>
                    </span>
                    <input type="text"
                           class="form-control text-end"
                           id="shippingCosts"
                           name="shippingCosts"
                           pattern="^\d*(\.\d{2}$)?"
                           placeholder="<?=($this->escape($this->get('settings')->getFixShippingCosts()) != '') ? $this->escape($this->get('settings')->getFixShippingCosts()) : '' ?>"
                           value="<?php if ($shopItem != '') {
                                      echo $this->escape($shopItem->getShippingCosts());
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

        <div class="row mb-3<?=$this->validation()->hasError('shippingTime') ? ' has-error' : '' ?>">
            <label for="shippingTime" class="col-xl-2 col-form-label">
                <?=$this->getTrans('shippingTime') ?>:
            </label>
            <div class="col-xl-5">
                <div class="input-group">
                    <input type="number"
                           class="form-control"
                           id="shippingTime"
                           name="shippingTime"
                           min="1"
                           placeholder="<?=($this->escape($this->get('settings')->getFixShippingTime()) != '') ? $this->escape($this->get('settings')->getFixShippingTime()) : '' ?>"
                           value="<?php if ($shopItem != '') {
                                      echo $this->escape($shopItem->getShippingTime());
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

        <div class="row mb-3<?=$this->validation()->hasError('image') ? ' has-error' : '' ?>">
            <label for="selectedImage_image" class="col-xl-2 col-form-label">
                <?=$this->getTrans('productThumbnail') ?>:
            </label>
            <div class="col-xl-5">
                <div class="input-group">
                    <span class="input-group-text">
                        <?php
                        if ($shopItem != '' && $shopItem->getImage() && file_exists(ROOT_PATH . '/' . $shopItem->getImage())) {
                            $img = BASE_URL . '/' . $shopItem->getImage();
                        } else {
                            $img = BASE_URL . $shopImgPath . 'noimg.jpg';
                        } ?>
                        <span class="fa-solid fa-eye" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-html="true" data-bs-title="<img src='<?=$img ?>' width='200' />"></span>
                    </span>
                    <input type="text"
                           class="form-control"
                           id="selectedImage_image"
                           name="image"
                           placeholder="<?=$this->getTrans('choosePic') ?>"
                           value="<?=($shopItem != '') ? $this->escape($shopItem->getImage()) : $this->escape($this->originalInput('image')) ?>" />
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

        <div class="row mb-3<?=$this->validation()->hasError('image1') ? ' has-error' : '' ?>">
            <label for="selectedImage_image1" class="col-xl-2 col-form-label">
                <?=$this->getTrans('productImage') ?> 1:
            </label>
            <div class="col-xl-5">
                <div class="input-group">
                    <span class="input-group-text">
                        <?php
                        if ($shopItem != '' && $shopItem->getImage1() && file_exists(ROOT_PATH . '/' . $shopItem->getImage1())) {
                            $img1 = BASE_URL . '/' . $shopItem->getImage1();
                        } else {
                            $img1 = BASE_URL . $shopImgPath . 'noimg.jpg';
                        } ?>
                        <span class="fa-solid fa-eye" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-html="true" data-bs-title="<img src='<?=$img1 ?>' width='200' />"></span>
                    </span>
                    <input type="text"
                           class="form-control"
                           id="selectedImage_image1"
                           name="image1"
                           placeholder="<?=$this->getTrans('choosePic') ?>"
                           value="<?=($shopItem != '') ? $this->escape($shopItem->getImage1()) : $this->escape($this->originalInput('image1')) ?>" />
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

        <div class="row mb-3<?=$this->validation()->hasError('image2') ? ' has-error' : '' ?>">
            <label for="selectedImage_image2" class="col-xl-2 col-form-label">
                <?=$this->getTrans('productImage') ?> 2:
            </label>
            <div class="col-xl-5">
                <div class="input-group">
                    <span class="input-group-text">
                        <?php
                        if ($shopItem != '' && $shopItem->getImage2() && file_exists(ROOT_PATH . '/' . $shopItem->getImage2())) {
                            $img2 = BASE_URL . '/' . $shopItem->getImage2();
                        } else {
                            $img2 = BASE_URL . $shopImgPath . 'noimg.jpg';
                        } ?>
                        <span class="fa-solid fa-eye" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-html="true" data-bs-title="<img src='<?=$img2 ?>' width='200' />"></span>
                    </span>
                    <input type="text"
                           class="form-control"
                           id="selectedImage_image2"
                           name="image2"
                           placeholder="<?=$this->getTrans('choosePic') ?>"
                           value="<?=($shopItem != '') ? $this->escape($shopItem->getImage2()) : $this->escape($this->originalInput('image2')) ?>" />
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

        <div class="row mb-3<?=$this->validation()->hasError('image3') ? ' has-error' : '' ?>">
            <label for="selectedImage_image3" class="col-xl-2 col-form-label">
                <?=$this->getTrans('productImage') ?> 3:
            </label>
            <div class="col-xl-5">
                <div class="input-group">
                    <span class="input-group-text">
                        <?php
                        if ($shopItem != '' && $shopItem->getImage3() && file_exists(ROOT_PATH . '/' . $shopItem->getImage3())) {
                            $img3 = BASE_URL . '/' . $shopItem->getImage3();
                        } else {
                            $img3 = BASE_URL . $shopImgPath . 'noimg.jpg';
                        } ?>
                        <span class="fa-solid fa-eye" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-html="true" data-bs-title="<img src='<?=$img3 ?>' width='200' />"></span>
                    </span>
                    <input type="text"
                           class="form-control"
                           id="selectedImage_image3"
                           name="image3"
                           placeholder="<?=$this->getTrans('choosePic') ?>"
                           value="<?=($shopItem != '') ? $this->escape($shopItem->getImage3()) : $this->escape($this->originalInput('image3')) ?>" />
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

        <div class="row mb-3<?=$this->validation()->hasError('info') ? ' has-error' : '' ?>">
            <label for="info" class="col-xl-2 col-form-label">
                <?=$this->getTrans('shortInfo') ?>:
            </label>
            <div class="col-xl-10">
                <textarea class="form-control ckeditor"
                          id="info"
                          name="info"
                          cols="50"
                          rows="3"
                          toolbar="ilch_html">
                          <?=($shopItem != '') ? $this->escape($shopItem->getInfo()) : $this->escape($this->originalInput('info')) ?>
                </textarea>
            </div>
        </div>

        <div class="row mb-3<?=$this->validation()->hasError('desc') ? ' has-error' : '' ?>">
            <label for="desc" class="col-xl-2 col-form-label">
                <?=$this->getTrans('description') ?>:
            </label>
            <div class="col-xl-10">
                <textarea class="form-control ckeditor"
                          id="desc"
                          name="desc"
                          cols="50"
                          rows="3"
                          toolbar="ilch_html">
                          <?=($shopItem != '') ? $this->escape($shopItem->getDesc()) : $this->escape($this->originalInput('desc')) ?>
                </textarea>
            </div>
        </div>
    </form>

    <h1><?=$this->getTrans('variants') ?></h1>
    <?php if ($propertyVariants) : ?>
        <p><?=$this->getTrans('variantsFollowingVariantsExist') ?></p>
        <form method="POST" action="<?=$this->getUrl(['controller' => 'items', 'action' => 'index']) ?>" id="propertyVariantsForm">
            <?=$this->getTokenField() ?>

            <div class="table-responsive">
                <table id="sortTable" class="table table-hover table-striped">
                    <colgroup>
                        <col class="icon_width">
                        <col class="icon_width">
                        <col class="icon_width">
                        <col class="icon_width">
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                    </colgroup>
                    <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_shops') ?></th>
                        <th></th>
                        <th></th>
                        <th class="text-center"><?=$this->getTrans('status') ?></th>
                        <th class="text-center"><?=$this->getTrans('productImage') ?></th>
                        <th class="sort"><?=$this->getTrans('productName') ?></th>
                        <th class="sort"><?=$this->getTrans('itemNumber') ?></th>
                        <th class="sort"><?=$this->getTrans('cat') ?></th>
                        <th class="text-end"><?=$this->getTrans('stock') ?></th>
                        <th class="text-end"><?=$this->getTrans('price') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($this->get('shopItems') as $shopItem) : ?>
                        <?php
                        $shopCats = $categoryMapper->getCategoryById($shopItem->getCatId());
                        $shopImgPath = '/application/modules/shop/static/img/';
                        if ($shopItem->getImage() && file_exists(ROOT_PATH . '/' . $shopItem->getImage())) {
                            $img = BASE_URL . '/' . $shopItem->getImage();
                        } else {
                            $img = BASE_URL . $shopImgPath . 'noimg.jpg';
                        }
                        ?>
                        <tr class="filter">
                            <td><?=$this->getDeleteCheckbox('check_shops', $shopItem->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $shopItem->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'delshop', 'id' => $shopItem->getId()]) ?></td>
                            <td class="text-center">
                                <?php
                                if ($shopItem->getStatus() == 1) {
                                    echo '<a href="' . $this->getUrl(['action' => 'treat', 'id' => $shopItem->getId()]) . '" class="btn btn-sm btn-success" title="' . $this->getTrans('active') . '"><i class="fa-solid fa-eye"></i></a>';
                                } else {
                                    echo '<a href="' . $this->getUrl(['action' => 'treat', 'id' => $shopItem->getId()]) . '" class="btn btn-sm btn-danger" title="' . $this->getTrans('inactive') . '"><i class="fa-solid fa-eye-slash inactiv"></i></a>';
                                }
                                ?>
                            </td>
                            <td class="text-center"><a href="<?=$this->getUrl(['action' => 'treat', 'id' => $shopItem->getId()]) ?>"><img src="<?=$img ?>" class="item_image <?=($shopItem->getCordon() == 1) ? $shopItem->getCordonColor() : ''; ?>" alt="<?=$this->escape($shopItem->getName()) ?>"/></a></td>
                            <td><?=$this->escape($shopItem->getName()) ?></td>
                            <td><?=$this->escape($shopItem->getItemnumber()) ?></td>
                            <td><?=($shopCats) ? $this->escape($shopCats->getTitle()) : ''; ?></td>
                            <td class="text-end">
                                <?php if ($this->escape($shopItem->getStock()) < 1) { ?>
                                    <button class="btn btn-sm btn-danger stock"><?=$this->escape($shopItem->getStock()) ?></button>
                                <?php } elseif ($this->escape($shopItem->getStock()) <= 5) { ?>
                                    <button class="btn btn-sm btn-warning stock"><?=$this->escape($shopItem->getStock()) ?></button>
                                <?php } else { ?>
                                    <button class="btn btn-sm btn-success stock"><?=$this->escape($shopItem->getStock()) ?></button>
                                <?php } ?>
                            </td>
                            <td class="text-end"><?=$this->escape($shopItem->getPrice()) ?> <?=$this->escape($this->get('currency')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </form>
    <?php endif; ?>

    <p><?=$this->getTrans('variantsDesc') ?></p>
    <div class="variant-properties-selection just-padding">
        <ul class="variant-properties-selection list-group list-group-root">
            <?php foreach ($properties as $property) : ?>
                <?php if (!$property->isEnabled()) : ?>
                    <?php continue; ?>
                <?php endif; ?>
                <li class="variant-properties-selection list-group-item">
                    <i class="fa-solid fa-fw fa-chevron-right collapseButton" id="collapseButton-<?=$property->getId() ?>" data-bs-toggle="collapse" data-bs-target="#item-<?=$property->getId() ?>"></i>
                    <input class="form-check-input me-1 propertyCheckbox" type="checkbox" value="<?=$property->getId() ?>" name="propertyCheckbox" id="propertyCheckbox-<?=$property->getId() ?>" data-property-id="<?=$property->getId() ?>" form="shopItemForm">
                    <label class="form-check-label" for="propertyCheckbox-<?=$property->getId() ?>"><?=$this->escape($propertiesTranslation[$property->getId()] ?? $property->getName()) ?></label>
                </li>

                <ul class="list-group collapse" id="item-<?=$property->getId() ?>">
                <?php foreach ($propertiesValues as $propertyValue) : ?>
                    <?php if ($propertyValue->getPropertyId() == $property->getId()) : ?>
                        <li class="list-group-item">
                            <input class="form-check-input me-1 valueCheckbox" type="checkbox" value="<?=$propertyValue->getId() ?>" name="valueCheckbox[]" id="valueCheckbox-<?=$propertyValue->getId() ?>" data-property-id="<?=$property->getId() ?>" form="shopItemForm">
                            <label class="form-check-label" for="valueCheckbox-<?=$propertyValue->getId() ?>"><?=$this->escape($propertiesValuesTranslations[$propertyValue->getId()] ?? $propertyValue->getValue()) ?></label>
                        </li>
                    <?php endif ?>
                <?php endforeach; ?>
                </ul>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="content_savebox">
        <?php if ($propertyVariants) : ?>
            <div class="btn-group dropup">
                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <?=$this->getTrans('selected') ?>
                </button>

                <ul class="dropdown-menu listChooser" role="menu">
                    <li><button type="submit" class="delete_button btn dropdown-item" id="delete" name="action" value="delete" form="propertyVariantsForm"><?=$this->getTrans('delete') ?></button></li>
                </ul>
            </div>
        <?php endif; ?>
        <button type="submit" class="save_button btn btn-secondary" name="saveShopItem" value="save" form="shopItemForm">
            <?=!empty($shopItem) ? $this->getTrans('updateButton') : $this->getTrans('addButton') ?>
        </button>
    </div>
<?php else : ?>
    <?=$this->getTrans('noCategory') ?>
<?php endif; ?>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<script>
$(function () {
    $("span .clearImage").click(function(){
        $(this).parent().siblings('input[type="text"]').val('');
    });

    $('.collapseButton').on('click', function() {
        $(this).closest('.fa-solid')
            .toggleClass('fa-chevron-right')
            .toggleClass('fa-chevron-down');
    });

    // Check all value checkboxes if the property checkbox is checked.
    $('.propertyCheckbox').on('click', function() {
        let id = $(this).attr('data-property-id');
        let propertyCheckboxState = $(this).prop('checked');

        $('#item-' + id).children().children('input').each(function () {
            this.checked = propertyCheckboxState;
        });
    });

    // Handling indeterminate checkbox states.
    $('.valueCheckbox').change(function(e) {
        let id = $(this).attr('data-property-id');
        let listGroup = $('#item-' + id);
        let checkboxes = listGroup.children().children('.valueCheckbox');
        let checkedCount = listGroup.children().children('.valueCheckbox:checked').length;
        let propertyCheckbox = $('#propertyCheckbox-' + id);

        propertyCheckbox.prop('checked', (checkedCount > 0));
        propertyCheckbox.prop('indeterminate', (checkedCount > 0 && checkedCount < checkboxes.length));
    });
});
</script>
