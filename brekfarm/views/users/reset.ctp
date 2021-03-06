<h2><?php echo $title = __('Forgotten Password', true); $this->set('title', $title); $html->addCrumb($title); ?></h2>
<?php echo $form->create('User', array('action' => 'reset', 'class' => 'hform')); ?> 
<?php echo $form->inputs(array(
	'User.email' => array(
		'label' => __('E-mail', true),
		'maxlength' => 128,
		'div' => 'input text required',
		'error' => array(
			'maxLength' => __('This email is too long to be a real one', true),
			'format' => __('This is not a valid email', true),
			'server' => __('This e-mail server is unknown by DNS, ask YOUR administrator for fix', true))),
	'legend' => __('User Account', true))); ?> 
<?php echo $form->end(__('Send', true)); ?> 
