<h2><?php echo $title = __('Producer Detail', true); $this->set('title', $title); $html->addCrumb($title); ?></h2>
<?php echo $this->element('producers' . DS . 'view', array('producer' => $producer['Producer'], 'isOwner' => $isOwner)); ?>
