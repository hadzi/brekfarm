<h2><?php echo $title = __('Set Owner', true); $this->set('title', $title); $html->addCrumb('Admin'); $html->addCrumb('Producers', array('controller' => 'producers', 'action' => 'index')); $html->addCrumb($title); ?></h2>
<?php echo $form->create('Producer', array('url' => array('action' => 'owner', $this->data['Producer']['id']), 'class' => 'hform')); ?>
<?php
	echo $form->inputs(array(
		'Producer.id',
		'User.email' => array(
			'label' => __('User Account Email', true),
			'maxlength' => 128,
			'error' => array(
				'notEmpty' => __('This field cannot be left blank', true),
				'hasProducer' => __('This user account already owns one producer', true),
				'isPromoter' => __('This user account is already promoter of this producer', true),
				'invalidOrUnused' => __('This email is either invalid or not used by any user account', true))),
		'legend' => __('Producer Ownership', true))); ?>
<?php echo $form->end(__('Send', true)); ?>
