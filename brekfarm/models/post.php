<?php
class SplittableBehavior extends ModelBehavior {
	protected $_defaults = array('field' => 'content', 'marker' => '<hr />');

	public function setup($Model, $config = array()) {
		$this->settings[$Model->alias] = array_merge($this->_defaults, $config);
	}

	public function split($Model) {
		//$this->settings[$Model->alias]
		//$Model->data[$Model->alias]
	}
}
?>