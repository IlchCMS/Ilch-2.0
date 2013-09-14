<div class="control-group">
	<label for="timezone" class="control-label">
		<?php echo $this->trans('timezone'); ?>:
	</label>
	<div class="controls">
		<select id="timezone" name="timezone">
			<?php 
				$timezones = $this->get('timezones');

				for($i = 0; $i < count($timezones); $i++)
				{
					$sel = '';
					if($this->get('timezone') == $timezones[$i])
					{
						$sel = 'selected="selected"';
					}

					echo '<option '.$sel.' value="'.$timezones[$i].'">'.$timezones[$i].'</option>';
				}
			?>
		</select>
	</div>
</div>