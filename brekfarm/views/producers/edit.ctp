<h2><?php echo $title = __('Producer Edit', true); $this->set('title', $title); $html->addCrumb($title); $html->addCrumb($this->data['Producer']['title']); ?></h2>
<?php echo $form->create('Producer', array('action' => 'edit', 'class' => 'hform')); ?>
<?php
	echo $form->inputs(array(
		'Producer.slug' => array(
			'type' => 'hidden'),
		'Producer.title' => array(
			'type' => 'hidden'),
		'Producer.address' => array(
			'label' => __('Address', true),
			'maxlength' => 255,
			'div' => 'input text required',
			'error' => __('This field cannot be left blank', true)),
		'Producer.phone' => array(
			'label' => __('Phone', true),
			'maxlength' => 16,
			'div' => 'input text required',
			'error' => array(
				'format' => __('This is not valid phone number', true),
				'isUnique' => __('This phone is taken', true))),
		'Producer.url' => array(
			'label' => __('Website URL', true),
			'maxlength' => 128,
			'error' => __('This is not valid website URL', true)),
		'Producer.description' => array(
			'label' => __('Description', true),
			'error' => __('This field cannot be left blank', true)),
		'legend' => __('Producer Account', true))); ?>
<?php echo $form->end(__('Send', true)); ?>
