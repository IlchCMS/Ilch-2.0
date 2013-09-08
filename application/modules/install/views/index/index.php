<h2>
	<?php echo $this->trans('welcomeToInstall', array('[VERSION]' => VERSION)); ?>
</h2>
<br />
<div class="control-group">
	<label for="languageInput" class="control-label">
		<?php echo $this->trans('chooseLanguage'); ?>:
	</label>
	<div class="controls">
	<select name="language" id="languageInput">
		<?php
		foreach($this->get('languages') as $key => $value)
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
<div class="control-group">
	<label for="timezone" class="control-label">
		<?php echo $this->trans('timezone'); ?>:
	</label>
	<div class="controls">
		<select id="timezone" name="timezone">
			<?php 
				$timezones = $this->get('timezones');

				for($i = 0; $i < count($timezones); $i++)
				{
					$sel = '';
					if($this->get('timezone') == $timezones[$i])
					{
						$sel = 'selected="selected"';
					}

					echo '<option '.$sel.' value="'.$timezones[$i].'">'.$timezones[$i].'</option>';
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
