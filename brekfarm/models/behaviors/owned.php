<?php
/**
 * Behavior which manages created_by and modified_by fields
 */
class OwnedBehavior extends ModelBehavior {
/**
 * beforeSave callback, initializes created_by or modified_by field values
 *
 * @param AppModel $Model
 * @return boolean
 * @access public
 */
	public function beforeSave($Model) {
		$field = $Model->exists() ? 'modified_by' : 'created_by';
		if ($Model->hasField($field)) {
			$Model->set($field, $this->_getUserId());
			$this->_addToWhitelist($Model, $field);
		}
		return true;
	}
/**
 * Returns ID of currently logged in user
 *
 * @return mixed
 * @access public
 */
	protected function _getUserId() {
		return (empty($_SESSION['Auth']['User']['id']) ? null : $_SESSION['Auth']['User']['id']);
	}
}
?>