<?php
$errors = $this->get('errors');
?>
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
<div class="control-group <?php if(!empty($errors['dbConnection'])){ echo 'error'; }; ?>">
	<label for="dbHost" class="control-label">
		<?php echo $this->trans('dbHost'); ?>:
	</label>
	<div class="controls">
		<input value="<?php if($this->get('dbHost') != ''){ echo $this->get('dbHost'); }else{ echo 'localhost'; } ?>"
			   type="text"
			   name="dbHost"
			   id="dbHost" />
		<?php
			if(!empty($errors['dbConnection']))
			{
				echo '<span class="help-inline">'.$this->trans($errors['dbConnection']).'</span>';
			}
		?>
	</div>
</div>
<div class="control-group <?php if(!empty($errors['dbConnection'])){ echo 'error'; }; ?>">
	<label for="dbUser" class="control-label">
		<?php echo $this->trans('dbUser'); ?>:
	</label>
	<div class="controls">
		<input value="<?php if($this->get('dbUser') != ''){ echo $this->get('dbUser'); } ?>"
			   type="text"
			   name="dbUser"
			   id="dbUser" />
	</div>
</div>
<div class="control-group <?php if(!empty($errors['dbConnection'])){ echo 'error'; }; ?>">
	<label for="dbPassword" class="control-label">
		<?php echo $this->trans('dbPassword'); ?>:
	</label>
	<div class="controls">
		<input value="<?php if($this->get('dbPassword') != ''){ echo $this->get('dbPassword'); } ?>"
			   type="password"
			   name="dbPassword"
			   id="dbPassword" />
	</div>
</div>
<div class="control-group <?php if(!empty($errors['dbDatabase'])){ echo 'error'; }; ?>">
	<label for="dbName" class="control-label">
		<?php echo $this->trans('dbName'); ?>:
	</label>
	<div class="controls">
		<input value="<?php if($this->get('dbName') != ''){ echo $this->get('dbName'); } ?>"
			   type="text"
			   name="dbName"
			   id="dbName" />
		<?php
			if(!empty($errors['dbDatabase']))
			{
				echo '<span class="help-inline">'.$this->trans($errors['dbDatabase']).'</span>';
			}
		?>
	</div>
</div>
<div class="control-group">
	<label for="dbPrefix" class="control-label">
		<?php echo $this->trans('dbPrefix'); ?>:
	</label>
	<div class="controls">
		<input value="<?php if($this->get('dbPrefix') != ''){ echo $this->get('dbPrefix'); } else { echo 'ilch_'; } ?>"
			   type="text"
			   name="dbPrefix"
			   id="dbPrefix" />
	</div>
</div>