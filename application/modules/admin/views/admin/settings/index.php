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
	<div class="control-group">
		<label for="maintenanceMode" class="control-label">
			<?php echo $this->trans('maintenanceMode'); ?>:
		</label>
		<div class="controls">
			<label class="radio">
				<input type="radio"
					   name="maintenanceMode"
					   id="maintenanceMode"
					   value="1"
				<?php if($this->get('maintenanceMode') == '1') { echo 'checked="checked"';} ?> />
				<?php echo $this->trans('on'); ?>
			</label>
			<label class="radio">
				<input type="radio"
					   name="maintenanceMode"
					   value="0"
				<?php if($this->get('maintenanceMode') != '1') { echo 'checked="checked"';} ?>>
				<?php echo $this->trans('off'); ?>
			</label>
		</div>
	</div>
	<button type="submit" name="save" class="btn">
		<?php echo $this->trans('saveButton'); ?>
	</button>
</form>