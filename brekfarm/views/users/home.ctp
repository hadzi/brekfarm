<h2><?php echo $title = __('Your Homepage', true); $this->set('title', $title); $html->addCrumb($title); ?></h2>
<ul class="switcher">
	<li><a href="#"><?php echo $user['User']['username']; ?></a></li>
	<?php if ($user['Producer']): ?>
	<li><a href="#"><?php echo $user['Producer']['title']; ?></a></li>
	<?php endif; ?>
	<li><a href="#"><?php __('Promoted Producers');?></a></li>
</ul>
<div class="panes">
	<div>
		<dl><?php $i = 0; $class = ' class="altrow"';?>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('E-mail'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo $user['User']['email']; ?>
				&nbsp;
			</dd>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Avatar for comments'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo $gravatar->image($user['User']['email']); ?>
				&nbsp;
			</dd>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name for comments'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo $user['User']['name']; ?>
				&nbsp;
			</dd>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Promo Code'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo $user['User']['promo_code']; ?>
				&nbsp;
			</dd>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Promo Rate'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo $user['User']['promo_rate']; ?>%
				&nbsp;
			</dd>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Payment Method'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo ($user['User']['payment_method'] ? $paymentMethods[$user['User']['payment_method']] : ''); ?>
				&nbsp;
			</dd>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Payment Target'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo $user['User']['payment_target']; ?>
				&nbsp;
			</dd>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo $time->format('d.m.Y', $user['User']['created']); ?>
				&nbsp;
			</dd>
		</dl>
		<ul class="tabs">
			<li><?php echo $html->link(__('Edit Profile', true), array('action' => 'edit')); ?></li>
			<li><?php echo $html->link(__('Edit Email or Password', true), array('action' => 'account')); ?></li>
		</ul>
	</div>
	<?php if ($user['Producer']): ?>
	<div>
		<?php echo $this->element('producers' . DS . 'view', array('producer' => $user['Producer'], 'isOwner' => true)); ?>
	</div>
	<?php endif; ?>
	<div>
		<?php if (empty($user['PromotedProducer'])):?>
		<p><?php __('We are sorry, no producer used your promo code for registration yet.'); ?><p>
		<?php else:?>
		<table>
		<tr>
			<th><?php __('Title'); ?></th>
			<th><?php __('Status'); ?></th>
			<th><?php __('Rating'); ?></th>
			<th><?php __('Comments'); ?></th>
			<th><?php __('Created'); ?></th>
			<th class="actions">&nbsp;</th>
		</tr>
		<?php
			$i = 0;
			foreach ($user['PromotedProducer'] as $producer):
				$class = null;
				if ($i++ % 2 == 0) {
					$class = ' class="altrow"';
				}
		?><tr<?php echo $class;?>>
			<td><?php echo $producer['url'] ? $html->link($producer['title'], $producer['url']) : $producer['title'];?></td>
			<td><?php echo $producer['status'];?></td>
			<td><?php echo $producer['rating_avg'];?></td>
			<td><?php echo $producer['comment_count'];?></td>
			<td><?php echo $time->format('d.m.Y', $producer['created']); ?></td>
			<td class="actions">
				<?php echo in_array($producer['status'], array('ok', 'email', 'delayed')) ? $html->link(__('View', true), array('controller' => 'producers', 'action' => 'view', $producer['slug'])) : ''; ?>
			</td>
		</tr><?php endforeach; ?>
		</table>
		<?php endif; ?>
	</div>
</div>
<?php $jquery->tabs(); ?>
