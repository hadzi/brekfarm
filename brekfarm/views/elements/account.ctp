<ul class="tabs">
<?php if ($username = $session->read('Auth.User.username')):?>
	<li><?php echo $html->link(sprintf(__('Signed in as %s', true), $username), array('controller' => 'users', 'action' => 'home', 'admin' => false)); ?></li>
	<li><?php echo $html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout', 'admin' => false)); ?></li>
<?php else:?>
	<li><?php echo $html->link(__('Sign In', true), array('controller' => 'users', 'action' => 'login')); ?></li>
	<li><?php echo $html->link(__('Sign Up', true), array('controller' => 'users', 'action' => 'add')); ?></li>
<?php endif;?>
</ul>
