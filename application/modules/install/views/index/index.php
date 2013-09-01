<h2>
	<?php echo $this->getTranslator()->trans('welcomeToInstall', array('[VERSION]' => VERSION)); ?>
</h2>
<br />
<div class="control-group">
	<label for="languageInput" class="control-label">
		<?php echo $this->getTranslator()->trans('chooseLanguage'); ?>:
	</label>
	<div class="controls">
	<select name="language" id="languageInput">
		<?php
		foreach($this->languages as $key => $value)
		{
			$selected = '';
	
			if($this->getTranslator()->getLocale() == $key)
			{
				$selected = 'selected="selected"';
			}

			echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
		}
		?>
	</select>
	</div>
</div>

<script>
	$('#languageInput').change
	(
		this,
		function()
		{
			top.location.href = '<?php echo $this->url(array('module' => 'install')); ?>&language='+$(this).val();
		}
	);
</script>
