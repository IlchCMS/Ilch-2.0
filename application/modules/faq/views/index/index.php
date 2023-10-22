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
        <div class="row mb-3">
            <div class="col-xl-6">
                <input type="text" class="form-control" placeholder="<?=$this->getTrans('placeHolderSearch') ?>" name="search" id="search">
            </div>
            <div class="col-xl-6">
                <button type="submit" class="btn btn-outline-secondary"><?=$this->getTrans('search') ?></button>
            </div>
        </div>
    </form>

    <nav class="navbar navbar-expand-lg border rounded bg-light mb-3">
        <div class="container-fluid">
            <a class="navbar-brand">Navigation</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#bs-example-navbar-collapse-1" aria-controls="bs-example-navbar-collapse-1" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php foreach ($categories as $category) :
                        $countFaqs = count($faqMapper->getFaqs(['f.cat_id' => $category->getId()], ['f.id' => 'ASC'], $readAccess));
                        if ($category->getId() == $this->getRequest()->getParam('catId')) {
                            $active = 'active';
                        } else {
                            $active = '';
                        }

                        if ($countFaqs > 0) : ?>
                            <li class="nav-item">
                                <a href="<?=$this->getUrl(['action' => 'index', 'catId' => $category->getId()]) ?>" <a class="nav-link <?=$active ?>">
                                    <b><?=$this->escape($category->getTitle()) ?></b>
                                    <span class="badge rounded-pill bg-secondary"><?=$countFaqs ?></span>
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
