<h2><?php echo $title = __('Add Product', true); $this->set('title', $title); $html->addCrumb($title); ?></h2>
<?php
	$inputs = array(
		'legend' => __('Add Product', true),
		'Product.description' => array(
			'label' => __('Description', true),
			'error' => __('This field cannot be left blank', true)),
		'Product.price' => array(
			'label' => __('Price', true),
			'maxlength' => 16,
			'error' => __('This is not valid price', true)),
		'Product.unit' => array(
		),
		'Product.category_id' => array(
			'label' => __('Category', true),
			'type' => 'select',
			'options' => $categories),
		'Product.producer_id' => array('type' => 'hidden'),
		'Product.approved_from' => array(
		),
		'Product.approved_to' => array(
		)
	);
	if ($this->Session->read('Auth.User.role') === 'admin') {
//		$inputs['Product.producer_id']['xxx'] = '';
	}
?>
<?php echo $form->create('Product', array('class' => 'hform')); ?>
<?php echo $form->inputs($inputs); ?>
<?php echo $form->end(__('Save as Draft', true)); ?>
