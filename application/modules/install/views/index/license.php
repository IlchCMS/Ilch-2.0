<div class="control-group <?php if(isset($this->error)){ echo 'error'; } ?>">
    <div class="controls">
		<textarea style="width: 100%; height: 300px;"><?php echo $this->licenceText; ?></textarea>
	</div>
	<div class="controls">
		<label class="checkbox inline">
			<input type="checkbox" name="licenceAccepted" value="1"> <?php echo $this->getTranslator()->trans('acceptLicence'); ?>
		</label>
    </div>
</div>
