<h2><?php echo $title = __('Account Edit', true); $this->set('title', $title); $html->addCrumb($title); ?></h2>
<?php echo $form->create('User', array('action' => 'account', 'class' => 'hform')); ?>
<?php echo $form->inputs(array(
	'User.passwd_old' => array(
			'label' => __('Current Password', true),
			'div' => 'input password required',
			'type' => 'password',
			'error' => __('Invalid password', true)),
	'User.email' => array(
		'label' => __('E-mail', true),
		'maxlength' => 128,
		'error' => array(
			'maxLength' => __('This email is too long to be a real one', true),
			'format' => __('This is not a valid email', true),
			'server' => __('This e-mail server is unknown by DNS, ask YOUR administrator for fix', true),
			'isUnique' => __('This email is taken', true),
			'isUniqueGlobally' => __('This email is taken', true))),
	'User.passwd' => array(
			'label' => __('Password', true),
			'error' => array(
				'minLength' => sprintf(__('Password length must be at least %s characters', true), 4),
				'matchUsername' => __('Password should not be the same as username', true),
				'matchPasswords' => __('Entered passwords does not match', true))),
	'User.passwd_check' => array(
		'label' => __('Password again', true),
		'type' => 'password'),
	'legend' => __('User Account', true))); ?>
<?php echo $form->end(__('Send', true)); ?>
