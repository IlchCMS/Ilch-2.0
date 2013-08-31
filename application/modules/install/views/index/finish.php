<h3><?php echo $this->getTranslator()->trans('successInstalled'); ?>!</h3>

<a target="_blank" href="<?php echo $this->url('index', 'index', 'index'); ?>" class="btn btn-medium btn-success">
	Frontend
</a> 
<a target="_blank" href="<?php echo $this->url('admin', 'index', 'index'); ?>" class="btn btn-medium btn-primary">
	Administration
</a>