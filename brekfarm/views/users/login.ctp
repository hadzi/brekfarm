<h2><?php echo $title = __('Sign In', true); $this->set('title', $title); $html->addCrumb($title); ?></h2>
<?php echo $form->create('User', array('action' => 'login', 'class' => 'hform')); ?> 
<?php echo $form->inputs(array(
	'User.username' => array(
		'label' => __('Username', true),
		'maxlength' => 64,
		'div' => 'input text required'),
	'User.passwd' => array(
		'label' => __('Password', true),
		'div' => 'input password required'),
	'User.remember_me' => array(
		'label' => sprintf(__('Remember me for %s days', true), 14),
		'type' => 'checkbox',
		'value' => 1),
	'legend' => __('Login Credentials', true))); ?> 
<?php echo $form->end(__('Sign In', true)); ?> 
<?php $resetLink = $html->link(__('Forgotten Password', true), array('action' => 'reset')); ?>
<p><?php echo sprintf(__('If you forgot Your password, visit following link %s', true), $resetLink); ?></p>
