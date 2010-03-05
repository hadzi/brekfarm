<?php
/**
 * Behavior for models with an email field
 */
class MailableBehavior extends ModelBehavior {
/**
 * beforeValidate callback, lowercases an email
 *
 * @param AppModel $Model
 * @return boolean
 * @access public
 */
	function beforeValidate($Model) {
		if (!empty($Model->data[$Model->alias]['email'])) {
			$Model->data[$Model->alias]['email'] = strtolower($Model->data[$Model->alias]['email']);
		}
		return true;
	}
}
?>