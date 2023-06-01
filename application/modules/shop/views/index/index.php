<?php
$categories = $this->get('categories');
$countCats = $this->get('countCats');
$shopItems = $this->get('shopItems');
$itemsMapper = $this->get('itemsMapper');
$readAccess = $this->get('readAccess');
$adminAccess = $this->get('adminAccess');
$status = '';

/* shopcart session */
if (isset($_POST['code']) && $_POST['code'] != '' && isset($_POST['itemid']) && $_POST['itemid'] != '' && is_numeric($_POST['itemid'])) {
    $code = $_POST['code'];
    $itemid = $_POST['itemid'];
    $shopItem = $itemsMapper->getShopItemById($itemid);
    $id = $shopItem->getId();
    $name = $shopItem->getName();
    $cartArray = [
        $code => [
        'id' => $id,
        'code' => $code,
        'quantity' => 1]
    ];

    if(empty($_SESSION['shopping_cart'])) {
        $_SESSION['shopping_cart'] = $cartArray;
        $status = '<div id="infobox" class="alert alert-success" role="alert">'.$this->getTrans('theProduct').' <b>'.$name.'</b> '.$this->getTrans('addToCart').'</div>';
    } else {
        $array_keys = array_keys($_SESSION['shopping_cart']);
        if(in_array($code,$array_keys)) {
            $status = '<div id="infobox" class="alert alert-danger" role="alert">'.$this->getTrans('theProduct').' <b>'.$name.'</b> '.$this->getTrans('alreadyInCart').'</div>';	
        } else {
            $_SESSION['shopping_cart'] = array_merge($_SESSION['shopping_cart'],$cartArray);
            $status = '<div id="infobox" class="alert alert-success" role="alert">'.$this->getTrans('theProduct').' <b>'.$name.'</b> '.$this->getTrans('addToCart').'</div>';
        }
    }
}

/* show shopcart */
$cart_badge = '';
if(!empty($_SESSION['shopping_cart'])) {
    $cart_count = count(array_keys($_SESSION['shopping_cart']));
    $cart_badge = ($cart_count>0)?'<a class="activecart" href="'.$this->getUrl('shop/index/cart').'#shopAnker">'.$this->getTrans('menuCart').'<i class="fa-solid fa-shopping-cart"><span class="badge">'.$cart_count.'</span></i></a>':'';
}
?>

<h1>
    <?=$this->getTrans('menuShops') ?>
    <?=$cart_badge ?>
    <div id="shopAnker"></div>
</h1>

<?php if (!empty($shopItems) && !empty($categories)) : ?>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand"><?=$this->getTrans('shopNavigation') ?></a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$this->getTrans('menuCats') ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <?php foreach ($categories as $category) :
                                $countCat = (isset($countCats[$category->getId()])) ? $countCats[$category->getId()] : 0;
                                if ($category->getId() == $this->get('firstCatId') || $category->getId() == $this->getRequest()->getParam('catId')) {
                                    $active = 'class="active"';
                                } else {
                                    $active = '';
                                }

                                if ($countCat > 0) : ?>
                                    <li <?=$active ?>>
                                        <a href="<?=$this->getUrl('shop/index/index/catId/' . $category->getId()) ?>#shopAnker">
                                            <?=$this->escape($category->getTitle()) ?>
                                            <span class="countItems">[<?=$countCat ?>]</span>
                                        </a>
                                        
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li><a href="<?=$this->getUrl('shop/customerarea/index') ?>#shopAnker"><?=$this->getTrans('menuCustomerArea') ?></a></li>
                    <li><a href="<?=$this->getUrl('shop/index/agb') ?>#shopAnker"><?=$this->getTrans('menuAGB') ?></a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <input type="text" id="search-items" class="form-control" placeholder="<?=$this->getTrans('itemSearch') ?>">
                        <ul id="item-list" class="dropdown-menu">
                            <?php
                            $allItems = $itemsMapper->getShopItems(['status' => 1]);
                            foreach ($allItems as $listItem) :
                                $shopImgPath = '/application/modules/shop/static/img/';
                                if ($listItem->getImage() && file_exists(ROOT_PATH . '/' . $listItem->getImage())) {
                                    $img = BASE_URL . '/' . $listItem->getImage();
                                } else {
                                    $img = BASE_URL . $shopImgPath . 'noimg.jpg';
                                } ?>
                                <li>
                                    <a href="<?=$this->getUrl('shop/index/show/id/' . $listItem->getId()) ?>#shopAnker">
                                        <img class="listImg" src="<?=$img ?>" alt="<?=$this->escape($listItem->getName()) ?>" />
                                        <span><?=$this->escape($listItem->getName()) ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                </ul>
            </div>

        </div>
    </nav>
<div class="message_box">
    <?=$status; ?>
</div>

<div class="row">
    <?php foreach ($shopItems as $shopItem) : ?>
    <div class="col-xs-6 col-md-4">
        <?php if ($shopItem->getCordon() && $shopItem->getCordon() == 1) { ?>
            <div class="cordon-wrapper">
                <div class="cordon <?=$this->escape($shopItem->getCordonColor()) ?>"><?=$this->escape($shopItem->getCordonText()) ?></div>
            </div>
        <?php } ?>
        <a class="thumbnail" href="<?=$this->getUrl('shop/index/show/id/' . $shopItem->getId()) ?>#shopAnker">
            <?php $shopImgPath = '/application/modules/shop/static/img/';
            if ($shopItem->getImage() && file_exists(ROOT_PATH . '/' . $shopItem->getImage())) {
                $img = BASE_URL . '/' . $shopItem->getImage();
            } else {
                $img = BASE_URL . $shopImgPath . 'noimg.jpg';
            } ?>
            <img src="<?=$img ?>" alt="<?=$this->escape($shopItem->getName()) ?>" />
            <div class="caption">
                <h4><?=$this->escape($shopItem->getName()) ?></h4>
                <p><?=$shopItem->getPrice() ?> <?=$this->escape($this->get('currency')) ?></p>
                <?php if ($shopItem->getStock() >= 1) { ?>
                    <form class="form" method="post" action="#shopAnker">
                        <?=$this->getTokenField() ?>
                        <input type="hidden" name="code" value="<?=$this->escape($shopItem->getCode()) ?>" />
                        <input type="hidden" name="itemid" value="<?=$shopItem->getId() ?>" />
                        <button type="submit" class="btn btn-sm btn-warning">
                            <small><?=$this->getTrans('inToCart') ?> <i class="fa-solid fa-shopping-cart"></i></small>
                        </button>
                    </form>
                <?php } else { ?>
                    <button class="btn btn-sm btn-default">
                        <small><?=$this->getTrans('currentlySoldOut') ?> <i class="fa-solid fa-store-slash"></i></small>
                    </button>
                <?php } ?>
            </div>
        </a> 
    </div>
    <?php endforeach; ?>
</div>
<div style="clear:both;"></div>
<script>
$(document).ready(function () {
    setTimeout(function() {
        $('#infobox').slideUp("slow");
    }, 5000);
    /* hide item-list */
    $("ul#item-list").hide();
    /* highlight */
    const highlight = function (string) {
        $("ul#item-list span.match").each(function () {
            const matchStart = $(this).text().toLowerCase().indexOf("" + string.toLowerCase() + "");
            const matchEnd = matchStart + string.length - 1;
            const beforeMatch = $(this).text().slice(0, matchStart);
            const matchText = $(this).text().slice(matchStart, matchEnd + 1);
            const afterMatch = $(this).text().slice(matchEnd + 1);
            $(this).html(beforeMatch + "<em>" + matchText + "</em>" + afterMatch);
        });
    };
    /* filter and show items */
    $("#search-items").on("keyup click input", function () {
        if (this.value.length > 0) {
            $("ul#item-list li").hide();
            $("ul#item-list span").removeClass("match").hide().filter(function () {
                return $(this).text().toLowerCase().indexOf($("#search-items").val().toLowerCase()) !== -1;
            }).addClass("match").show().closest("li").show();
            highlight(this.value);
            $("ul#item-list").show();
        } else {
            $("ul#item-list, ul#item-list span").removeClass("match").hide();
        }
    });
    
});
</script>
<?php else : ?>
    <?=$this->getTrans('noItems') ?>
<?php endif; ?>
