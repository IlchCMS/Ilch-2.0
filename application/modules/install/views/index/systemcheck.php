System überprüfung
<form method="POST" action="<?php echo $this->url('install', 'index', 'systemcheck'); ?>">
	<input type="hidden" value="1" name="save" />
	<button type="submit" class="btn">Next</button>
</form>
