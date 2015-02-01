<?php
if ($this->get('war') != '') {
foreach ($this->get('war') as $war) : ?>
    <div class="games-schedule-items">
        <div class="row games-team">
            <div class="col-md-4">
                <img src="http://placehold.it/115x67" alt="<?php echo $this->escape($war->getWarEnemy()); ?>">
                <span><?php echo $this->escape($war->getWarEnemy()); ?></span>
            </div>
            <div class="col-md-4">
                <h4 class="img-circle">VS</h4>
            </div>
            <div class="col-md-4">
                <img src="http://placehold.it/115x67" alt="<?php echo $this->escape($war->getWarGroup()); ?>">
                <span><?php echo $this->escape($war->getWarGroup()); ?></span>
            </div>
        </div>
        <div class="row games-info">
            <div class="col-md-12">
                <p><span class="glyphicon glyphicon-play-circle"></span> <?php echo $this->escape($war->getWarTime()); ?></p>
                <p class="games-dash"></p>
                <p><small><i class="fa fa-shield"></i><?php echo $this->escape($war->getWarGame()); ?></small></p>
            </div>
        </div>
    </div>
<?php endforeach; 
} else {
    echo $this->getTranslator()->trans('noWars');
}
?>
