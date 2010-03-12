<h2><?php echo $title = __('Producers', true); $this->set('title', $title); $html->addCrumb('Admin'); $html->addCrumb($title); ?></h2>
<table>
<tr>
	<th><?php echo $paginator->sort(__('Title', true), 'title');?></th>
	<th><?php echo $paginator->sort(__('Slug', true), 'slug');?></th>
	<th><?php __('Address');?></th>
	<th><?php echo $paginator->sort(__('Phone', true), 'phone');?></th>
	<th><?php echo $paginator->sort(__('E-mail', true), 'email');?></th>
	<th><?php echo $paginator->sort(__('Website URL', true), 'url');?></th>
	<th><?php echo $paginator->sort(__('Client Code', true), 'client_code');?></th>
	<th><?php echo $paginator->sort(__('Status', true), 'status');?></th>
	<th><?php echo $paginator->sort(__('Created', true), 'created');?></th>
	<th><?php __('Modified');?></th>
	<th class="actions">&nbsp;</th>
</tr>
<?php
$i = 0;
foreach ($producers as $producer):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $producer['Producer']['title']; ?>
		</td>
		<td>
			<?php echo $producer['Producer']['slug']; ?>
		</td>
		<td>
			<?php echo $producer['Producer']['address']; ?>
		</td>
		<td>
			<?php echo $producer['Producer']['phone']; ?>
		</td>
		<td>
			<?php echo $producer['Producer']['email']; ?>
		</td>
		<td>
			<?php echo $producer['Producer']['url'] ? $html->link($producer['Producer']['url']) : ''; ?>
		</td>
		<td>
			<?php echo $producer['Producer']['client_code']; ?>
		</td>
		<td>
			<?php echo $producer['Producer']['status']; ?>
		</td>
		<td>
			<?php echo $time->format('d.m.Y', $producer['Producer']['created']); ?>
		</td>
		<td>
			<?php echo $time->format('d.m.Y', $producer['Producer']['modified']); ?>
		</td>
		<td class="actions">
			<?php
				if (empty($producer['Producer']['user_id'])) {
					echo $html->link(__('Set Owner', true), array('action' => 'owner', $producer['Producer']['id']));
				}
			?>
			<?php
				if ($producer['Producer']['status'] == 'denied') {
					echo $html->link(__('Allow', true), array('action' => 'allow', $producer['Producer']['id']));
				} else {
					echo $html->link(__('Deny', true), array('action' => 'deny', $producer['Producer']['id']));
                                }
			?>
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
