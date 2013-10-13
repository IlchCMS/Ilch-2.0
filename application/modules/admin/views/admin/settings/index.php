<form class="form-horizontal" method="POST" action="<?php echo $this->url(array('action' => $this->getRequest()->getActionName())); ?>">
	<legend><?php echo $this->trans('systemSettings'); ?></legend>
	<div class="form-group">
		<label for="languageInput" class="col-lg-2 control-label">
			<?php echo $this->trans('chooseLanguage'); ?>:
		</label>
		<div class="col-lg-2">
			<select class="form-control" name="language" id="languageInput">
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
	<div class="form-group">
		<label for="maintenanceMode" class="col-lg-2 control-label">
			<?php echo $this->trans('maintenanceMode'); ?>:
		</label>
		<div class="col-lg-2">
			<div class="radio">
				<label>
					<input type="radio"
					   name="maintenanceMode"
					   id="maintenanceMode"
					   value="1"
				<?php if($this->get('maintenanceMode') == '1') { echo 'checked="checked"';} ?> /> <?php echo $this->trans('on'); ?>
				</label>
			</div>
			<div class="radio">
				<label>
					<input type="radio"
					   name="maintenanceMode"
					   value="0"
				<?php if($this->get('maintenanceMode') != '1') { echo 'checked="checked"';} ?>> <?php echo $this->trans('off'); ?>
				</label>
			</div>
		</div>
	</div>
	<div class="content_savebox">
		<button type="submit" name="save" class="btn">
			<?php echo $this->trans('saveButton'); ?>
		</button>
	</div>
</form>
