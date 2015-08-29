<textarea class="form-control" style="width: 100%; height: 300px;" disabled><?=$this->escape($this->get('licenceText')) ?></textarea>
<label class="checkbox inline <?php if ($this->get('error') != '') { echo 'text-danger'; } ?>" style="margin-left: 20px;">
    <input type="checkbox" name="licenceAccepted" value="1"> <?=$this->getTrans('acceptLicence') ?>
</label>
