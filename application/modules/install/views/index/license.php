		<textarea style="width: 100%; height: 300px;"><?php echo $this->licenceText; ?></textarea>
		<label class="checkbox inline">
			<input type="checkbox" name="licenceAccepted" value="1"> <?php echo $this->getTranslator()->trans('acceptLicence'); ?>
		</label>
