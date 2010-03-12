<?php if ($isOwner): ?>
<ul class="tabs">
	<li><?php echo $html->link(__('Add Product', true), array('controller' => 'products', 'action' => 'add')); ?></li>
</ul>
<?php endif; ?>
