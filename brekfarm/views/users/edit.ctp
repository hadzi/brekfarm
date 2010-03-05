<h2><?php echo $title = __('Profile Edit', true); $this->set('title', $title); $html->addCrumb($title); ?></h2>
<?php echo $form->create('User', array('action' => 'edit', 'class' => 'hform')); ?>
<?php echo $form->inputs(array(
		'User.username' => array(
			'type' => 'hidden'),
		'User.name' => array(
			'label' => __('Name for comments', true),
			'maxlength' => 64,
			'error' => array(
				'maxLength' => sprintf(__('Maximum length of public name is %s characters', true), 64))),
		'User.payment_method' => array(
			'label' => __('Payment Method', true),
			'type' => 'select',
			'error' => array(
				'inList' => __('Invalid option', true),
				'complement' => __('Specify both (or none) payment settings, please', true)),
			'options' => $paymentMethods,
			'empty' => __('-- select --', true)),
		'User.payment_target' => array(
			'label' => __('Payment Target', true),
			'error' => array(
				'complement' => __('Specify both (or none) payment settings, please', true))),
		'legend' => __('User Account', true))); ?>
<?php echo $form->end(__('Send', true)); ?>
