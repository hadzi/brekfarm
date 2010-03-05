<dl><?php $i = 0; $class = ' class="altrow"';?>
	<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo $producer['title']; ?>
		&nbsp;
	</dd>
	<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Address'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo $producer['address']; ?>
		&nbsp;
	</dd>
	<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Phone'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo $producer['phone']; ?>
		&nbsp;
	</dd>
	<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Email'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo $producer['email']; ?>
		&nbsp;
	</dd>
	<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Url'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo $producer['url']; ?>
		&nbsp;
	</dd>
	<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Description'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo $producer['description']; ?>
		&nbsp;
	</dd>
	<?php if ($isOwner): ?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Client Code'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $producer['client_code']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Status'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $producer['status']; ?>
			&nbsp;
		</dd>
	<?php endif; ?>
	<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo $time->format('d.m.Y', $producer['created']); ?>
		&nbsp;
	</dd>
</dl>
<?php if ($isOwner): ?>
<ul class="tabs">
	<li><?php echo $html->link(__('Edit Profile', true), array('controller' => 'producers', 'action' => 'edit')); ?></li>
	<li><?php echo $html->link(__('Edit Email', true), array('controller' => 'producers', 'action' => 'email')); ?></li>
</ul>
<?php endif; ?>
