<h2><?php echo $title = sprintf(__('New %s Category', true), $this->data['Category']['model']); $this->set('title', $title); $html->addCrumb($title); ?></h2>
<?php echo $form->create('Category', array('class' => 'hform')); ?>
<?php echo $form->inputs(array(
	'Category.model' => array(
		'type' => 'hidden'),
	'Category.title' => array(
		'label' => __('Title', true)),
	'Category.parent_id' => array(
		'label' => __('Parent Category', true),
		'type' => 'select',
		'options' => $categories,
		'empty' => __('-- none --', true)),
	'legend' => __('Category', true))); ?>
<?php echo $form->end('Create');?>
