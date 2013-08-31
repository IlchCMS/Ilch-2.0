<div class="control-group">
	<label for="dbEngine" class="control-label">
		<?php echo $this->getTranslator()->trans('dbEngine'); ?>:
	</label>
	<div class="controls">
		<select name="dbEngine" id="dbEngine">
			<option value="Mysql">Mysql</option>
		</select>
	</div>
</div>
<div class="control-group">
	<label for="dbHost" class="control-label">
		<?php echo $this->getTranslator()->trans('dbHost'); ?>:
	</label>
	<div class="controls">
		<input value="<?php if(!empty($this->dbHost)){ echo $this->dbHost; }else{ echo 'localhost'; } ?>"
			   type="text"
			   name="dbHost"
			   id="dbHost" />
	</div>
</div>
<div class="control-group">
	<label for="dbUser" class="control-label">
		<?php echo $this->getTranslator()->trans('dbUser'); ?>:
	</label>
	<div class="controls">
		<input value="<?php if(!empty($this->dbUser)){ echo $this->dbUser; } ?>"
			   type="text"
			   name="dbUser"
			   id="dbUser" />
	</div>
</div>
<div class="control-group">
	<label for="dbPassword" class="control-label">
		<?php echo $this->getTranslator()->trans('dbPassword'); ?>:
	</label>
	<div class="controls">
		<input value="<?php if(!empty($this->dbPassword)){ echo $this->dbPassword; } ?>"
			   type="text"
			   name="dbPassword"
			   id="dbPassword" />
	</div>
</div>
<div class="control-group">
	<label for="dbName" class="control-label">
		<?php echo $this->getTranslator()->trans('dbName'); ?>:
	</label>
	<div class="controls">
		<input value="<?php if(!empty($this->dbName)){ echo $this->dbName; } ?>"
			   type="text"
			   name="dbName"
			   id="dbName" />
	</div>
</div>
<div class="control-group">
	<label for="dbPrefix" class="control-label">
		<?php echo $this->getTranslator()->trans('dbPrefix'); ?>:
	</label>
	<div class="controls">
		<input value="<?php if(!empty($this->dbPrefix)){ echo $this->dbPrefix; } ?>"
			   type="text"
			   name="dbPrefix"
			   id="dbPrefix" />
	</div>
</div>