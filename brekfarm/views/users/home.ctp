<h2><?php echo $title = __('Your Homepage', true); $this->set('title', $title); $html->addCrumb($title); ?></h2>
<ul class="switcher">
	<li><a href="#"><?php echo $user['User']['username']; ?></a></li>
	<?php if ($user['Producer']): ?>
	<li><a href="#"><?php echo $user['Producer']['title']; ?></a></li>
	<?php endif; ?>
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
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Promoted Producers'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
                                <?php if (empty($user['PromotedProducer'])):?>
                                <?php __('No producer used your promo code for registration yet.'); ?>
                                <?php else:?>                            
                		<?php foreach ($user['PromotedProducer'] as $producer):?>
                                    <p><?php echo $producer['url'] ? $html->link($producer['title'], $producer['url']) : $producer['title'];?></p>
                                <?php endforeach; ?>
                		<?php endif; ?>
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
</div>
<?php $jquery->tabs(); ?>
