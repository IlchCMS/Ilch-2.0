<legend>Gästebuch </legend>

<div class="table-responsive">
	<?php if (is_array($this->get('entries')) && count($this->get('entries')) > 0) : ?>
		<?php foreach ($this->get('entries') as $entry) : ?>
			<table class="table table-bordered table-striped table-responsive">
				<tbody>
					<tr>
						<td>Von: <?php echo $this->escape($entry['name']); ?></td>
						<td><a target="_blank" href="//<?php echo $this->escape($entry['homepage']); ?>"><span class="glyphicon glyphicon-home"></span></a>
							<a target="_blank" href="mailto:<?php echo $this->escape($entry['email']); ?>"><span class="glyphicon glyphicon-envelope"></span></a></td>
						<td>Datum: <?php echo $this->escape($entry['datetime']); ?></td>
					</tr>
				</tbody>
			</table>
            <div class="responsive panel-body"><?php echo $entry['text']; ?></div>
		<?php endforeach; ?>
	<?php endif; ?>
<hr>
	<form class="navbar-form navbar-left" action="<?php $this->url(); ?>/index.php/guestbook/index/newEntry" method="post">
		<p>
			Name<br />
			<input type="text" name="name" class="form-control" placeholder="Name" />
		</p>
		<p>
			Email<br />
			<input type="text" name="email" class="form-control" placeholder="E-Mail" />
		</p>
		<p>
			Homepage<br />
			<input type="text" name="homepage" class="form-control" placeholder="Homepage" />
		</p>

		<p>
			Nachricht<br />
			<textarea name="text" cols="40" rows="5"></textarea>
		</p>
		<p><input type="submit" name="saveEntry" value="Eintragen" /></p>
	</form>
</div>

<script type="text/javascript" src="<?php echo $this->staticUrl('js/tinymce/tinymce.min.js') ?>"></script>
<script>
    tinymce.init
            (
                    {
                        height: 400,
                        selector: "textarea",
                        plugins: "image preview"

                    }
            );
</script>