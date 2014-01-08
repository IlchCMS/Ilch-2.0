<legend>Neue Kategorie anlegen</legend>

<form action="<?php $this->url(); ?>/index.php/admin/gallery/index/newcatEntry" method="post"><?php echo $this->getTokenField(); ?>
    <p>
        <label>Name</label><br />
        
		<input type="text" class="form-control" placeholder="Name" name="name"/>

    </p>
    <p>
        Beschreibung<br />
        
		<textarea class="form-control" rows="3" name="besch" placeholder="Beschreibung"></textarea>

    </p>
    <p><input class="btn btn-default" type="submit" name="saveEntry" value="Eintragen" role="button" /><a class="btn btn-default pull-right" href="javascript:history.back()" role="button">Abbrechen</a></p>
</form>

 
