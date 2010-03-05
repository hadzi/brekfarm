<h2><?php echo $title = __('Categories', true); $this->set('title', $title); $html->addCrumb('Admin'); $html->addCrumb($title); ?></h2>
<ul class="switcher">
	<li><a href="#Article"><?php __('Article Categories'); ?></a></li>
	<li><a href="#Product"><?php __('Product Categories'); ?></a></li>
</ul>
<div class="panes">
	<div>
		<?php echo $tree->generate($categories['Article'], array('model' => 'Category', 'alias' => 'title')); ?>
		<ul class="tabs">
			<li><?php echo $html->link(__('Add Article Category', true), array('controller' => 'categories', 'action' => 'add', 'Article')); ?> </li>
		</ul>
	</div>
	<div>
		<?php echo $tree->generate($categories['Product'], array('model' => 'Category', 'alias' => 'title')); ?>
		<ul class="tabs">
			<li><?php echo $html->link(__('Add Product Category', true), array('controller' => 'categories', 'action' => 'add', 'Product')); ?> </li>
		</ul>
	</div>
</div>
<?php $jquery->tabs(); ?>
