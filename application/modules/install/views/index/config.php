<div class="control-group">
	<label for="type" class="control-label">
		<?php echo $this->trans('cmsType'); ?>:
	</label>
	<div class="controls">
		<select name="cmsType">
			<option value="private">Private</option>
			<option value="clan">Clan</option>
		</select>
	</div>
</div>
<hr />
<div class="control-group <?php if(!empty($this->errors['adminName'])){ echo 'error'; }; ?>">
	<label for="adminName" class="control-label">
		<?php echo $this->trans('adminName'); ?>:
	</label>
	<div class="controls">
		<input value="<?php if(!empty($this->adminName)){ echo $this->adminName; } ?>"
			   type="text"
			   name="adminName"
			   id="adminName" />
		<?php
			if(!empty($this->errors['adminName']))
			{
				echo '<span class="help-inline">'.$this->trans($this->errors['adminName']).'</span>';
			}
		?>
	</div>
</div>
<div class="control-group <?php if(!empty($this->errors['adminPassword'])){ echo 'error'; }; ?>">
	<label for="adminPassword" class="control-label">
		<?php echo $this->trans('adminPassword'); ?>:
	</label>
	<div class="controls">
		<input value="<?php if(!empty($this->adminPassword)){ echo $this->adminPassword; } ?>"
			   type="text"
			   name="adminPassword"
			   id="adminPassword" />
		<?php
			if(!empty($this->errors['adminPassword']))
			{
				echo '<span class="help-inline">'.$this->trans($this->errors['adminPassword']).'</span>';
			}
		?>
	</div>
</div>
<div class="control-group <?php if(!empty($this->errors['adminPassword2'])){ echo 'error'; }; ?>">
	<label for="adminPassword2" class="control-label">
		<?php echo $this->trans('adminPassword2'); ?>:
	</label>
	<div class="controls">
		<input value="<?php if(!empty($this->adminPassword2)){ echo $this->adminPassword2; } ?>"
			   type="text"
			   name="adminPassword2"
			   id="adminPassword2" />
		<?php
			if(!empty($this->errors['adminPassword2']))
			{
				echo '<span class="help-inline">'.$this->trans($this->errors['adminPassword2']).'</span>';
			}
		?>
	</div>
</div>
<div class="control-group <?php if(!empty($this->errors['adminEmail'])){ echo 'error'; }; ?>">
	<label for="adminEmail" class="control-label">
		<?php echo $this->trans('adminEmail'); ?>:
	</label>
	<div class="controls">
		<input value="<?php if(!empty($this->adminEmail)){ echo $this->adminEmail; } ?>"
			   type="text"
			   name="adminEmail"
			   id="adminEmail" />
		<?php
			if(!empty($this->errors['adminEmail']))
			{
				echo '<span class="help-inline">'.$this->trans($this->errors['adminEmail']).'</span>';
			}
		?>
	</div>
</div>