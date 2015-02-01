<?php foreach ($this->get('groups') as $group) : ?>
<div class="col-lg-12">
    <div class="well well-sm">
        <div class="row">
            <div class="col-xs-3 col-md-3 text-center">
                <img src="<?php echo $this->escape($group->getGroupImage()); ?>" alt=""
                    class="img-rounded img-responsive" />
            </div>
            <div class="col-xs-9 col-md-9 section-box">
                <h2>
                    <?php echo $this->escape($group->getGroupName()); ?> <a href=""><span class="glyphicon glyphicon-new-window">
                    </span></a>
                </h2>
                <p>...</p>
                <hr />
                <div class="row rating-desc">
                    <div class="col-md-12">
                        <span>WIN</span>(36)<span class="separator">|</span>
                        <span>LOOS</span>100)
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<style>
.glyphicon { margin-right:5px;}
.section-box h2 { margin-top:0px;}
.section-box h2 a { font-size:15px; }
.glyphicon-heart { color:#e74c3c;}
.glyphicon-comment { color:#27ae60;}
.separator { padding-right:5px;padding-left:5px; }
.section-box hr {margin-top: 0;margin-bottom: 5px;border: 0;border-top: 1px solid rgb(199, 199, 199);}
</style>
