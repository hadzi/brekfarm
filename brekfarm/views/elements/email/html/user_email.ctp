<h2><?php __('Email Change'); ?></h2>
<p><?php echo sprintf(__('To confirm change of your user account email at %s, visit following URL within 24 hours.', true), $host); ?></p>
<p><a href="<?php echo $url; ?>"><?php echo $url; ?></a></p>