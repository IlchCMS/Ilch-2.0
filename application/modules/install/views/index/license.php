<textarea style="width: 100%; height: 300px;"><?php echo $this->licenceText; ?></textarea>
<label class="checkbox inline <?php if(isset($this->error)){ echo 'text-error'; } ?>">
	<input type="checkbox" name="licenceAccepted" value="1"> <?php echo $this->trans('acceptLicence'); ?>
</label>