<h2>Gallery Show</h2>
<?php if (is_array($this->get('entrys')) && count($this->get('entrys')) > 0) : ?>
	<div class="row">
		<?php foreach ($this->get('entrys') as $entrys) : ?>
			<div class="col-lg-4 col-sm-6 col-xs-12"><a href="<?php $this->url(); ?>/index.php/admin/gallery/index/del/id/<?php echo $this->escape($entrys['id']); ?>/cat/<?php echo $this->escape($entrys['cat']); ?>"><img src="<?php echo $this->staticUrl($this->escape($entrys['url'])); ?>" class="thumbnail img-responsive"></img></a></div>
                
		<?php endforeach; ?>           
</div>

<?php endif; ?>