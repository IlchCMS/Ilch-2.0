<?php
/** @var \Ilch\View $this */

/** @var Modules\Faq\Models\Category[]|null $categories */
$categories = $this->get('categories');

/** @var Modules\Faq\Models\Faq[]|null $faqs */
$faqs = $this->get('faqs');

/** @var Modules\Faq\Mappers\Faq $faqMapper */
$faqMapper = $this->get('faqMapper');

/** @var Modules\Faq\Models\Faq[]|null $searchresult */
$searchresult = $this->get('searchresult');

/** @var bool $searchExecuted */
$searchExecuted = $this->get('searchExecuted') ?? false;

/** @var array $readAccess */
$readAccess = $this->get('readAccess');
?>

<h1><?=$this->getTrans('faqFrequentlyAskedQuestions') ?></h1>

<?php if ($searchExecuted) : ?>
    <?php if ($searchresult) : ?>
        <?=$this->getTrans('mightAnswerYourQuestion') ?>
    <ul>
        <?php foreach ($searchresult as $result) : ?>
        <li><a href="<?=$this->getUrl(['action' => 'show', 'id' => $result->getId()]) ?>"><b><?=$this->escape($result->getQuestion()) ?></b></a></li>
        <?php endforeach; ?>
    </ul>
    <?php else : ?>
    <p><?=$this->getTrans('noSearchResult') ?></p>
    <?php endif; ?>
<?php endif; ?>

<?php if ($faqs) : ?>
    <form class="form-horizontal" role="search" method="POST">
        <?=$this->getTokenField() ?>
        <div class="form-group">
            <div class="col-lg-6">
                <input type="text" class="form-control" placeholder="<?=$this->getTrans('placeHolderSearch') ?>" name="search" id="search">
            </div>
            <button type="submit" class="btn btn-default"><?=$this->getTrans('search') ?></button>
        </div>
    </form>

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
                    <?php foreach ($categories as $category) :
                        $countFaqs = count($faqMapper->getFaqs(['f.cat_id' => $category->getId()], ['f.id' => 'ASC'], $readAccess));
                        if ($category->getId() == $this->getRequest()->getParam('catId')) {
                            $active = 'class="active"';
                        } else {
                            $active = '';
                        }

                        if ($countFaqs > 0) : ?>
                            <li <?=$active ?>>
                                <a href="<?=$this->getUrl(['action' => 'index', 'catId' => $category->getId()]) ?>">
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
        <?php foreach ($faqs as $faq) : ?>
            <li class="list-group-item"><a href="<?=$this->getUrl(['action' => 'show', 'id' => $faq->getId()]) ?>"><b><?=$this->escape($faq->getQuestion()) ?></b></a></li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <ul class="list-group">
        <li class="list-group-item"><?=$this->getTrans('noFaqs') ?></li>
    </ul>
<?php endif; ?>
