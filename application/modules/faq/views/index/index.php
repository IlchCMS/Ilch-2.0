<?php
$categories = $this->get('categorys');
$faqs = $this->get('faqs');
$faqMapper = new Modules\Faq\Mappers\Faq();
?>

<legend><?=$this->getTrans('faqFrequentlyAskedQuestions') ?></legend>
<?php if ($faqs != ''): ?>
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
                    <?php foreach ($categories as $category): ?>
                        <?php $countFaqs = count($faqMapper->getFaqs(array('cat_id' => $category->getId()))); ?>
                        <?php if ($category->getId() == $this->getRequest()->getParam('catId') OR $category->getId() == $this->get('firstCatId')) {
                            $active = 'class="active"';        
                        } else {
                            $active = '';                        
                        }
                        ?>
                        <?php if ($countFaqs > 0): ?>
                            <li <?=$active ?>>
                                <a href="<?=$this->getUrl('faq/index/index/catId/'.$category->getId()) ?>">
                                    <b><?=$category->getTitle() ?></b>
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
            <li class="list-group-item"><a href="<?=$this->getUrl('faq/index/show/id/'.$faq->getId()) ?>"><b><?=$faq->getQuestion() ?></b></a></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <ul class="list-group">
        <li class="list-group-item"><?=$this->getTrans('noFaqs') ?></li>
    </ul>
<?php endif; ?>
