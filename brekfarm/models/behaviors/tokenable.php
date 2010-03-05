<?php
/**
 * Behavior which manages model tokens and unique 'code' fields
 */
class TokenableBehavior extends ModelBehavior {
/**
 * Default model settings
 *
 * @var array
 * @access protected
 */
	protected $_defaults = array(
		'field' => null,
		'length' => 16,
		'possible' => null,
		'notStartWith' => null
	);
/**
 * Behavior setup
 *
 * @param AppModel $Model
 * @param array $config
 * @return void
 * @access public
 */
	public function setup($Model, $config = array()) {
		$this->settings[$Model->alias] = array_merge($this->_defaults, $config);
	}
/**
 * beforeSave callback, initializes unique code fields
 *
 * @param AppModel $Model
 * @return boolean
 * @access public
 */
	public function beforeSave($Model) {
		extract($this->settings[$Model->alias]);
		if ($field && !$Model->exists()) {
			do {
				$token = $this->generateToken($Model, $length, $possible, $notStartWith);
			} while ((bool) $Model->find('count', array('conditions' => array($field => $token))));
			$Model->set($field, $token);
			$this->_addToWhitelist($Model, $field);
		}
		return true;
	}
/**
 * Check if token exists
 *
 * @param AppModel $Model
 * @param string $name
 * @param string $token
 * @return mixed string ID or false
 * @access public
 */
	public function checkToken($Model, $name, $token) {
		$id = $Model->Token->field('foreign_key', array(
				'model' => $Model->name,
				'name' => $name,
				'value' => $token));
		return (empty($id) ? false : $id);
	}
/**
 * Create, save and pass token to ModelName::$tokens
 *
 * @param AppModel $Model
 * @param string $name
 * @param mixed $id
 * @return mixed string token or false
 * @access public
 */
	public function createToken($Model, $name, $id = null) {
		$id = empty($id) ? $Model->getID() : $id;
		if (empty($id) || (bool) $Model->Token->find('count', array('conditions' => array(
			'model' => $Model->name,
			'foreign_key' => $id,
			'name' => $name
		)))) {
			return false;
		}
		do {
			$token = $this->generateToken($Model);
		} while ((bool) $Model->Token->find('count', array('conditions' => array('value' => $token))));
		$data = array('Token' => array(
			'model' => $Model->name,
			'foreign_key' => $id,
			'name' => $name,
			'value' => $token
		));
		$Model->Token->create($data);
		if ($Model->Token->save()) {
			return ($Model->tokens[$name] = $token);
		}
		return false;
	}
/**
 * generate new token
 *
 * @param AppModel $Model
 * @param int $length
 * @param mixed $possible
 * @param mixed $notStartWith
 * @return string
 * @access public
 */
	public function generateToken($Model, $length = 16, $possible = null, $notStartWith = null) {
		$possible = empty($possible) ? '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' : $possible;
		$scope = strlen($possible) - 1;
		$token = '';
		$i = 0;
		while ($i < $length) {
			$char = substr($possible, mt_rand(0, $scope), 1);
			if ($i === 0 && !is_null($notStartWith) && $char === $notStartWith) {
				continue;
			}
			$token .= $char;
			$i++;
		}
		return $token;
	}
/**
 * Token Removal
 *
 * @param AppModel $Model
 * @param string $name
 * @param string $token
 * @return void
 * @access public
 */
	public function removeToken($Model, $name, $token) {
		$Model->Token->deleteAll(array(
			'model' => $Model->name,
			'name' => $name,
			'value' => $token
		), false);
	}
}
?>