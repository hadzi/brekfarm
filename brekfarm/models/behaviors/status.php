<?php
/**
 * Behavior which manages model statuses
 */
class StatusBehavior extends ModelBehavior {
/**
 * Behavior setup
 *
 * @param AppModel $Model
 * @param array $config
 * @return void
 * @access public
 */
	public function setup($Model, $config = array()) {
		$statuses = array_keys($config['status']);
		if (!isset($Model->validate['status'])) {
			$Model->validate['status'] = array(
				'inList' => array(
					'rule' => array('inList', $statuses)),
				'validPath' => array(
					'rule' => 'validateStatus',
					'on' => 'update')
			);
		}
		if (empty($Model->statuses)) {
			$Model->statuses = $statuses;
		}
		$this->settings[$Model->alias] = $config;
	}
/**
 * One-purpose method for switching statuses
 *
 * @param AppModel $Model
 * @param string $status
 * @param string $id
 * @return boolean
 */
	public function setStatus($Model, $status, $id = null) {
		if ($id) {
			$Model->id = $id;
		}
		return $Model->saveField('status', $status, true);
	}
/**
 * Method for validation of status change flow
 *
 * @param AppModel $Model
 * @param array $data
 * @return boolean
 * @access public
 */
	public function validateStatus($Model, $data) {
		if ($Model->exists() && ($current = $Model->field('status'))) {
			return in_array($data['status'], $this->settings[$Model->alias]['status'][$current]);
		}
		return false;
	}
}
?>