<?php
$errors = $this->get('errors');
$userArray = $this->get('users');
?>

<style>
.left-box{
	width: 60%;
	float: left;
}

.right-box{
	width: 40%;
	float: right;
}

br{
	clear: both;
}
</style>

<h4><?=$this->getTrans('createEvent');?></h4>
<form action="<?=$this->getUrl(array('action' => 'create'));?>" method="POST" id="standart">
	<?=$this->getTokenField()?>
	<div class="left-box">
	
		<div class="form-group <?php if (!empty($errors['name'])) { echo 'has-error'; }; ?>">
			<label for="name" class="control-label col-lg-3">
				<?php echo $this->getTrans('title'); ?>:
			</label>
			<div class="col-lg-9">
				<input value=""
					   type="text"
					   name="name"
					   class="form-control"
					   id="name" />
			</div>
		</div>
		
		<div class="form-group">
			<label for="organizer" class="control-label col-lg-3">
				<?php echo $this->getTrans('organizer'); ?>:
			</label>
            <select name="organizer"
                    id="groupId"
                    class="form-control">
                <?php foreach($userArray as $user) :?>
                    <option value="<?=$user->getId();?>"
                            <?=$user->getName();?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
		
	</div>

	<div class="right-box">

	</div>
</form>
<br />

<pre>
<?php
print_r( $userArray );
?>
</pre>