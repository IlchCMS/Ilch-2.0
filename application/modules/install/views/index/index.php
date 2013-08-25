<h2>
    <?php echo $this->getTranslator()->trans('welcomeToInstall', array('[VERSION]' => VERSION)); ?>
</h2>
<br />
<?php echo $this->getTranslator()->trans('chooseLanguage'); ?>

<div class="control-group">
    <label for="languageInput" class="control-label">
	<?php echo $this->getTranslator()->trans('language'); ?>:
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
	    top.location.href = '<?php echo $this->url('install', 'index', 'index'); ?>&language='+$(this).val();
	}
    );
</script>
