<?php
//        Router::parseExtensions('htm', 'html', 'txt', 'rss', 'js', 'json');
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
?>