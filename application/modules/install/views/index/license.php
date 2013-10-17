<textarea class="form-control" style="width: 100%; height: 300px;"><?php echo $this->escape($this->get('licenceText')); ?></textarea>
<label class="checkbox inline <?php if($this->get('error') != ''){ echo 'text-danger'; } ?>">
	<input type="checkbox" name="licenceAccepted" value="1"> <?php echo $this->trans('acceptLicence'); ?>
</label>