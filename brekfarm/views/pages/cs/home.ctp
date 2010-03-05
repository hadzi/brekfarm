<?php $title = __('Main Page', true); $this->set('title', $title); ?>
<div class="form">
<?php echo $form->create('Producer', array('url' => '#', 'class' => 'hform')); ?> 
<?php echo $form->inputs(array(
	'Producer.location' => array(),
	'Product.name' => array('label' => __('Product', true)),
	'Producer.distance' => array(),
	'legend' => __('Search Form', true))); ?> 
<?php echo $form->end(__('Search', true)); ?> 
</div>
