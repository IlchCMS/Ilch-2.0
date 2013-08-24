<h2>Welcome to Ilch CMS <?php echo VERSION; ?> installation!</h2>
<form method="POST" action="<?php echo $this->url('install', 'index', 'index'); ?>">
	<input type="hidden" value="1" name="save" />
	<button type="submit" class="btn">Next</button>
</form>
