<?php echo $html->doctype('xhtml-trans'); ?> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $html->charset(); ?>
	<title><?php echo $title_for_layout; ?></title>
	<?php echo $html->css('screen', null, array('media' => 'screen')); ?> 
	<!--[if lte IE 6]><?php echo $html->css('ie6', null, array('media' => 'screen')); ?><![endif]-->
	<?php echo $html->css('print', null, array('media' => 'print')); ?> 
	<?php echo $scripts_for_layout; ?> 
</head>
<body>
<div id="page">
	<div id="header">
		<div id="account">
<?php echo $this->element('account'); ?>
		</div>
		<h1>Logo + Header</h1>
	</div>
	<div id="navigation">
<?php echo $menu->root(); ?>
	</div>
	<div id="body" class="wrapper">
		<div id="leftSideBar" class="sideBar">
<?php echo $this->element('sidebox' . DS . 'menu'); ?>
<?php echo $this->element('sidebox' . DS . 'ads'); ?>
		</div>
		<div id="content">
<?php echo $menu->breadcrumbs(); ?>
<?php echo $this->element('flash'); ?>
<?php echo $content_for_layout; ?>
		</div>
		<div id="rightSideBar" class="sideBar">
<?php echo $this->element('sidebox' . DS . 'articles_recent'); ?>
<?php echo $this->element('sidebox' . DS . 'producers_top'); ?>
		</div>
	</div>
	<div id="footer">
		<p class="quiet"><small><?php echo  sprintf(__('By use of this website, you agree to the %s', true), $html->link(__('Terms of Service', true), array('controller' => 'pages', 'action' => 'display', 'tos', 'admin' => false))); ?></small></p>
<?php echo $menu->root('footMenu'); ?>
	</div>
</div>
<?php echo $menu->javascript(); ?>
<?php echo $bottom_for_layout; ?>
<?php echo $cakeDebug; ?>
</body>
</html>