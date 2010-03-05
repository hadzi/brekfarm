<?php
/**
 * Shield model
 */
class Shield extends AppModel {
/**
 * Custom database table name
 *
 * @var string
 * @access public
 */
	public $useTable = 'shield';
/**
 * List of used behaviors
 *
 * @var array
 * @access public
 */
	public $actsAs = array(
		'Owned'
	);
/**
 * Request info
 *
 * @var array
 * @access public
 */
	public $request = array(
		'ip' => null,
		'here' => null,
		'user_agent' => null,
		'referrer' => null,
		'referer' => null
	);
/**
 * Create new Shield record
 *
 * With severity 0, only basic dataset will be saved
 *
 * @param string $message
 * @param mixed $value
 * @param integer $severity 0 - 10
 * @param mixed $model
 * @param mixed $foreign_key
 * @return void
 * @access public
 */
	public function record($message, $value = null, $severity = 0, $model = null, $foreign_key = null) {
		$data = $severity ? $this->__advancedData($message, $value, $severity, $model, $foreign_key)
			: $this->__basicData($message, $value, $model, $foreign_key);
		$this->create($data);
		$this->save();
	}
/**
 * Prepare attack data for saving
 *
 * @param string $message
 * @param mixed $value
 * @param integer $severity
 * @param mixed $model
 * @param mixed $foreign_key
 * @return array
 * @access private
 */
	private function __advancedData($message, $value, $severity, $model, $foreign_key) {
		$data = $this->__basicData($message, $value, $model, $foreign_key);
		$user_agent = $this->request['user_agent'];
		$referrer = $this->request['referrer'];
		$referer = $this->request['referer'];
		$data['Shield'] = array_merge($data['Shield'], compact('severity', 'user_agent', 'referrer', 'referer'));
		return $data;
	}
/**
 * Prepare action data for saving
 *
 * @param string $message
 * @param mixed $value
 * @param mixed $model
 * @param mixed $foreign_key
 * @return array
 * @access private
 */
	private function __basicData($message, $value, $model, $foreign_key) {
		$value = $this->__normalizeValue($value);
		$ip = $this->request['ip'];
		$here = $this->request['here'];
		return array('Shield' => compact('model', 'foreign_key', 'message', 'value', 'ip', 'here'));
	}
/**
 * normalize value for saving
 *
 * @param mixed $value
 * @return mixed
 * @access private
 */
	private function __normalizeValue($value) {
		if (is_string($value)) {
			return $value;
		} elseif (is_numeric($value)) {
			return (string) $value;
		} elseif (empty($value)) {
			return null;
		} else {
			return serialize($value);
		}
	}
}
?>