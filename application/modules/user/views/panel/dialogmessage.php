<?php if ($this->get('inbox') != ''): ?>
    <?php foreach ($this->get('inbox') as $inbox): ?>
        <?php $date = new \Ilch\Date($inbox->getTime()); ?>
        <li class="<?=$inbox->getId() == ($this->getUser()->getId()) ? 'right' : 'left'; ?>">
            <div class="body">
                <div class="message well well-sm">
                    <?=$this->getHtmlFromBBCode($inbox->getText()) ?>
                </div>
            </div>
            <small class="timestamp">
                <?php
                if (strtotime($date) <= strtotime('-7 day')) {
                    echo $date->format('d.m.Y H:i', true);
                } elseif (strtotime($date) <= strtotime('-2 day') AND strtotime($date) >= strtotime('-6 day') ) {
                    echo $this->getTrans($date->format('l', true)).$date->format(' H.i', true);
                } elseif (strtotime($date) <= strtotime('-1 day')) {
                    echo $this->getTrans('profileYesterday').' '.$date->format('H:i', true);
                } else {
                    echo $date->format('H:i', true);
                };
                ?>
            </small>
        </li>
    <?php endforeach; ?>
<?php endif; ?>
