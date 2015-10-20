<?php if (!empty($this->get('jobs'))): ?>
    <ul class="list-unstyled">
        <?php foreach ($this->get('jobs') as $job): ?>
            <li>
                <a href="<?=$this->getUrl('jobs/index/show/id/' . $job->getId()) ?>">
                    <?=$job->getTitle() ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <?=$this->getTrans('noJobs') ?>
<?php endif; ?>
