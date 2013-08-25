<h2>
    <?php echo $this->getTranslator()->trans('welcomeToInstall', array('[VERSION]' => VERSION)); ?>
</h2>
<form method="POST" action="<?php echo $this->url('install', 'index', 'index'); ?>">
	<input type="hidden" value="1" name="save" />
	<button type="submit" class="btn">Next</button>
</form>