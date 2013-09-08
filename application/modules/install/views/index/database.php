<div class="control-group">
	<label for="dbEngine" class="control-label">
		<?php echo $this->trans('dbEngine'); ?>:
	</label>
	<div class="controls">
		<select name="dbEngine" id="dbEngine">
			<option value="Mysql">Mysql</option>
		</select>
	</div>
</div>
<div class="control-group <?php if(!empty($this->errors['dbConnection'])){ echo 'error'; }; ?>">
	<label for="dbHost" class="control-label">
		<?php echo $this->trans('dbHost'); ?>:
	</label>
	<div class="controls">
		<input value="<?php if(!empty($this->dbHost)){ echo $this->dbHost; }else{ echo 'localhost'; } ?>"
			   type="text"
			   name="dbHost"
			   id="dbHost" />
		<?php
			if(!empty($this->errors['dbConnection']))
			{
				echo '<span class="help-inline">'.$this->trans($this->errors['dbConnection']).'</span>';
			}
		?>
	</div>
</div>
<div class="control-group <?php if(!empty($this->errors['dbConnection'])){ echo 'error'; }; ?>">
	<label for="dbUser" class="control-label">
		<?php echo $this->trans('dbUser'); ?>:
	</label>
	<div class="controls">
		<input value="<?php if(!empty($this->dbUser)){ echo $this->dbUser; } ?>"
			   type="text"
			   name="dbUser"
			   id="dbUser" />
	</div>
</div>
<div class="control-group <?php if(!empty($this->errors['dbConnection'])){ echo 'error'; }; ?>">
	<label for="dbPassword" class="control-label">
		<?php echo $this->trans('dbPassword'); ?>:
	</label>
	<div class="controls">
		<input value="<?php if(!empty($this->dbPassword)){ echo $this->dbPassword; } ?>"
			   type="text"
			   name="dbPassword"
			   id="dbPassword" />
	</div>
</div>
<div class="control-group <?php if(!empty($this->errors['dbDatabase'])){ echo 'error'; }; ?>">
	<label for="dbName" class="control-label">
		<?php echo $this->trans('dbName'); ?>:
	</label>
	<div class="controls">
		<input value="<?php if(!empty($this->dbName)){ echo $this->dbName; } ?>"
			   type="text"
			   name="dbName"
			   id="dbName" />
		<?php
			if(!empty($this->errors['dbDatabase']))
			{
				echo '<span class="help-inline">'.$this->trans($this->errors['dbDatabase']).'</span>';
			}
		?>
	</div>
</div>
<div class="control-group">
	<label for="dbPrefix" class="control-label">
		<?php echo $this->trans('dbPrefix'); ?>:
	</label>
	<div class="controls">
		<input value="<?php if(!empty($this->dbPrefix)){ echo $this->dbPrefix; } else { echo 'ilch_'; } ?>"
			   type="text"
			   name="dbPrefix"
			   id="dbPrefix" />
	</div>
</div>