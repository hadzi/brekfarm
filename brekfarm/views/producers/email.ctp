<h2><?php echo $title = __('Producer Email Change', true); $this->set('title', $title); $html->addCrumb($title); ?></h2>
<?php echo $form->create('Producer', array('action' => 'email', 'class' => 'hform')); ?>
<?php echo $form->inputs(array(
	'Producer.email' => array(
		'label' => __('E-mail', true),
		'maxlength' => 128,
		'div' => 'input text required',
		'error' => array(
			'maxLength' => __('This email is too long to be a real one', true),
			'format' => __('This is not a valid email', true),
			'server' => __('This e-mail server is unknown by DNS, ask YOUR administrator for fix', true),
			'isUnique' => __('This email is taken', true),
			'isUniqueGlobally' => __('This email is taken', true))),
	'legend' => __('Producer Account', true))); ?>
<?php echo $form->end(__('Send', true)); ?> 
