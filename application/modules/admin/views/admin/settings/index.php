<form class="form-horizontal" method="POST" action="<?php echo $this->url(array('action' => $this->getRequest()->getActionName())); ?>">
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
	<button type="submit" name="save" class="btn">
		<?php echo $this->trans('saveButton'); ?>
	</button>
</form>