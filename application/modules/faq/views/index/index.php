<?php
$categories = $this->get('categories');
$faqs = $this->get('faqs');
$faqMapper = $this->get('faqMapper');
$readAccess = $this->get('readAccess');
$adminAccess = $this->get('adminAccess');
$searchresult = $this->get('searchresult');
?>

<h1><?=$this->getTrans('faqFrequentlyAskedQuestions') ?></h1>

<?php if (!empty($searchresult)) : ?>
    <?=$this->getTrans('mightAnswerYourQuestion') ?>
    <ul>
    <?php foreach ($searchresult as $result) : ?>
        <li><a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'id' => $result->getId()]) ?>"><b><?=$this->escape($result->getQuestion()) ?></b></a></li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if (!empty($faqs)): ?>
    <ul class="list-group">
        <form class="form-horizontal" role="search" method="POST">
            <?=$this->getTokenField() ?>
            <div class="form-group col-lg-6">
                <input type="text" class="form-control" placeholder="<?=$this->getTrans('placeHolderSearch') ?>" name="search" id="search">
            </div>
            <button type="submit" class="btn btn-default"><?=$this->getTrans('search') ?></button>
        </form>
    </ul>

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand">Navigation</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php foreach ($categories as $category):
                        if (!($adminAccess == true || is_in_array($readAccess, explode(',', $category->getReadAccess())))) {
                            continue;
                        }

                        $countFaqs = count($faqMapper->getFaqs(['cat_id' => $category->getId()]));
                        if ($category->getId() == $this->get('firstCatId') || $category->getId() == $this->getRequest()->getParam('catId')) {
                            $active = 'class="active"';
                        } else {
                            $active = '';
                        }

                        if ($countFaqs > 0): ?>
                            <li <?=$active ?>>
                                <a href="<?=$this->getUrl('faq/index/index/catId/'.$category->getId()) ?>">
                                    <b><?=$this->escape($category->getTitle()) ?></b>
                                    <span class="badge"><?=$countFaqs ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </nav>
    <ul class="list-group">
        <?php foreach ($faqs as $faq): ?>
            <li class="list-group-item"><a href="<?=$this->getUrl('faq/index/show/id/'.$faq->getId()) ?>"><b><?=$this->escape($faq->getQuestion()) ?></b></a></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <ul class="list-group">
        <li class="list-group-item"><?=$this->getTrans('noFaqs') ?></li>
    </ul>
<?php endif; ?>
