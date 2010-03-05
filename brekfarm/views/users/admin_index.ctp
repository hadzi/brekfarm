<h2><?php echo $title = __('Users', true); $this->set('title', $title); $html->addCrumb('Admin'); $html->addCrumb($title); ?></h2>
<table>
<tr>
	<th><?php echo $paginator->sort(__('Username', true), 'username');?></th>
	<th><?php echo $paginator->sort(__('E-mail', true), 'email');?></th>
	<th><?php echo $paginator->sort(__('Promo Code', true), 'promo_code');?></th>
	<th><?php __('Promo Rate'); ?></th>
	<th><?php echo $paginator->sort(__('Status', true), 'status');?></th>
	<th><?php echo $paginator->sort(__('User Role', true), 'role');?></th>
	<th><?php echo $paginator->sort(__('Created', true), 'created');?></th>
	<th><?php __('Modified');?></th>
</tr>
<?php
$i = 0;
foreach ($users as $user):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $user['User']['username']; ?>
		</td>
		<td>
			<?php echo $user['User']['email']; ?>
		</td>
		<td>
			<?php echo $user['User']['promo_code']; ?>
		</td>
		<td>
			<?php echo $user['User']['promo_rate']; ?>%
		</td>
		<td>
			<?php echo $user['User']['status']; ?>
		</td>
		<td>
			<?php echo $user['User']['role']; ?>
		</td>
		<td>
			<?php echo $time->format('d.m.Y', $user['User']['created']); ?>
		</td>
		<td>
			<?php echo $time->format('d.m.Y', $user['User']['modified']); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
<p class="counter">
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled'));?>
</div>
