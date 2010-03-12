<div class="products form">
<?php echo $form->create('Product');?>
	<fieldset>
 		<legend><?php __('Add Product');?></legend>
	<?php
		echo $form->input('description');
		echo $form->input('price');
		echo $form->input('unit');
		echo $form->input('category_id');
		echo $form->input('producer_id');
		echo $form->input('status');
		echo $form->input('approved_from');
		echo $form->input('approved_to');
		echo $form->input('weight');
		echo $form->input('created_by');
		echo $form->input('modified_by');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
