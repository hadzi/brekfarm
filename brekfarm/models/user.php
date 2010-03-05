<?php
/**
 * User model
 *
 * @property Geocode $Geocode
 * @property Comment $Comment
 * @property Favourite $Favourite
 * @property Producer $Producer
 * @property Rating $Rating
 * @property Refund $Refund
 * @property SavedSearch $SavedSearch
 */
class User extends AppModel {
/**
 * List of used behaviors
 *
 * @var array
 * @access public
 */
	public $actsAs = array(
		'Status' => array(
			'status' => array(
				'new' => array(		// waiting for email token verification
					'ok',
					'denied'),
				'invited' => array(	// waiting for email token verification, user invited to join some producer account as an owner
					'ok',
					'denied'),
				'ok' => array(		// active user account
					'email',
					'denied'),
				'email' => array(	// waiting for email-change token verification, possible only in 'ok' state
					'ok',
					'denied'),
				'denied' => array(	// deactivated user account
					'ok'))),
		'Geocoded',
		'Owned',
		'SpamDetector',
		'Tokenable' => array(
			'field' => 'promo_code',
			'length' => 6,
			'possible' => '0123456789abcdefghijklmnopqrstuvwxyz'),
		'Mailable'
	);
/**
 * List of belongsTo associations
 *
 * @var array
 * @access public
 */
	public $belongsTo = array(
		'Geocode'
	);
/**
 * List of hasMany associations
 *
 * @var array
 * @access public
 */
	public $hasMany = array(
		'Comment' => array(
			'foreignKey' => 'created_by',
			'dependent' => true),
		'Favourite' => array(
			'dependent' => true),
		/*
		 * In fact, relation User->Producer is hasOne, but CakePHP uses SQL conditions so
		 * related record is required. This is not the case in this application,
		 * so restriction on only one related Producer is done in app logic.
		 * @see ProducersController::add()
		 * @see ProducersController::admin_owner()
		 * @see Producer::setOwner()
		 * 
		 * As of hasMany relation User->PromotedProducers, it would create nothing but
		 * another reference to instance of model Producer, so it is omitted.
		 */
		'Producer' => array(
			'dependent' => false),
		'Rating' => array(
			'foreignKey' => 'created_by',
			'dependent' => true),
		'Refund' => array(
			'conditions' => array(
				'Refund.model' => 'User'),
			'foreignKey' => 'foreign_key',
			'dependent' => true),
		'SavedSearch' => array(
			'dependent' => true),
		'ShieldRecord' => array(
			'className' => 'Shield',
			'foreignKey' => 'created_by',
			'dependent' => true),
		'Token' => array(
			'conditions' => array(
				'Token.model' => 'User'),
			'foreignKey' => 'foreign_key',
			'dependent' => true)
	);
/**
 * validation rules
 *
 * @var array
 * @access public
 */
	public $validate = array(
		'username' => array(
			'between' => array(
				'rule' => array('between', 4, 64),
				'last' => true),
			'characters' => array(
				'rule' => array('custom', '/^[a-zA-Z0-9]*$/i'),
				'last' => true),
			'isUnique' => 'isUnique',
		),
		'name' => array(
			'maxLength' => array(
				'required' => false,
				'allowEmpty' => true,
				'rule' => array('maxLength', 64))),
		'passwd' => array(
			'minLength' => array(
				'rule' => array('minLength', 4),
				'last' => true),
			'matchUsername' => array(
				'rule' => array('matchUsername', 'username'),
				'last' => true),
			'matchPasswords' => array(
				'rule' => array('matchPasswords', 'passwd_check'))),
		'email' => array(
			'maxLength' => array(
				'rule' => array('maxLength', 128),
				'last' => true),
			'format' => array(
				'rule' => array('email'),
				'last' => true),
			'server' => array(
				'rule' => array('email', true),
				'last' => true),
			'isUnique' => 'isUnique',
			'isUniqueGlobally' => 'isUniqueEmail'),
		'tos' => array(
			'allowEmpty' => false,
			'rule' => array('custom', '[1]')),
		'payment_method' => array(
			'inList' => array(
				'required' => false,
				'allowEmpty' => true,
				'rule' => array('inList', array('bank', 'cheque')),
				'last' => true),
			'complement' => array(
				'required' => false,
				'allowEmpty' => true,
				'rule' => array('nonEmptyComplement', 'payment_target'))),
		'payment_target' => array(
			'complement' => array(
				'required' => false,
				'allowEmpty' => true,
				'rule' => array('nonEmptyComplement', 'payment_method')))
	);
/**
 * Statuses
 *
 * @var array
 * @access public
 */
	public $statuses = array();
/**
 * Fresh tokens
 *
 * @var array
 * @access public
 */
	public $tokens = array();
/**
 * Custom methods
 *
 * @var array
 * @access public
 */
	public $_findMethods = array('home' => true);
/**
 * beforeSave callback
 *
 * @param array $options
 * @return boolean
 * @access public
 */
	public function beforeSave($options = array()) {
		if (!parent::beforeSave($options)) return false;
		$this->hashPasswords(null, true);
		return true;
	}
/**
 * afterSave callback
 *
 * @param boolean $created
 * @return void
 * @access public
 */
	public function afterSave($created) {
		parent::afterSave($created);
		if ($created) {
			$this->createToken('email');
		}
	}
/**
 * beforeValidate callback
 *
 * @param array $options
 * @return boolean
 * @access public
 */
	public function beforeValidate($options = array()) {
		if (!parent::beforeValidate($options)) return false;
		foreach (array('name', 'payment_method', 'payment_target') as $field) {
			if (isset($this->data[$this->alias][$field]) && empty($this->data[$this->alias][$field])) {
				$this->data[$this->alias][$field] = null;
			}
		}
		return true;
	}
/**
 * Hash passwords, used from beforeSave() and AuthComponent
 *
 * @param mixed $data
 * @param boolean $enforce
 * @return mixed array or void
 * @access public
 */
	public function hashPasswords($data, $enforce = false) {
		if (!$enforce) {
			return $data;
		}
		if (!empty($this->data[$this->alias]['passwd'])) {
			$this->data[$this->alias]['passwd'] = Security::hash($this->data[$this->alias]['passwd'], null, true);
		}
	}
/**
 * Method for email validation across producers/users
 *
 * @param array $data
 * @return boolean
 * @access public
 */
	public function isUniqueEmail($data) {
		$conditions = array('email' => $data['email']);
		if ($this->exists()) {
			$conditions['OR'] = array(
				'user_id =' => null,
				'user_id !=' => $this->getID());
		}
		return ! $this->Producer->hasAny($conditions);
	}
/**
 * custom validation for password match
 *
 * @param array $data
 * @param string $fieldTwo
 * @return boolean
 * @access public
 */
	public function matchPasswords($data, $fieldTwo) {
		if (isset($this->data[$this->alias][$fieldTwo]) && $data['passwd'] == $this->data[$this->alias][$fieldTwo]) {
			return true;
		}
		$this->invalidate($fieldTwo);
		return false;
	}
/**
 * custom validation for username != password
 *
 * @param array $data
 * @param string $fieldTwo
 * @return boolean
 * @access public
 */
	public function matchUsername($data, $fieldTwo) {
		return (!isset($this->data[$this->alias][$fieldTwo]) || (low($data['passwd']) != low($this->data[$this->alias][$fieldTwo])));
	}
/**
 * custom validation for non-empty complement field
 *
 * @param array $data
 * @param string $fieldTwo
 * @return boolean
 * @access public
 */
	public function nonEmptyComplement($data, $fieldTwo) {
		if (empty($this->data[$this->alias][$fieldTwo])) {
			$this->invalidate($fieldTwo, 'complement');
			return false;
		}
		return true;
	}
/**
 * Custom find method for users homepage
 *
 * @param string $state
 * @param array $query
 * @param array $data
 * @return array
 * @access protected
 */
	protected function _findHome($state, $query, $results = array()) {
		if ($state == 'before') {
			$query['limit'] = 1;
			if ($this->exists()) {
				$this->bindModel(array('hasMany' => array(
					'PromotedProducer' => array(
						'className' => 'Producer',
						'foreignKey' => 'promoter_id'))));
				$query['fields'] = array(
					'username',
					'email',
					'name',
					'promo_code',
					'promo_rate',
					'payment_method',
					'payment_target',
					'created');
				$query['contain'] = array(
					'Producer.title',
					'Producer.address',
					'Producer.phone',
					'Producer.email',
					'Producer.url',
					'Producer.description',
					'Producer.client_code',
					'Producer.status',
					'Producer.created',
					'PromotedProducer.slug',
					'PromotedProducer.title',
					'PromotedProducer.url',
					'PromotedProducer.status',
					'PromotedProducer.rating_avg',
					'PromotedProducer.comment_count',
					'PromotedProducer.created');
				$query['conditions'] = array(
					'id' => $this->getID());
			} else {
				// unitialized model, do not return anything
				$query['fields'] = array('id');
				$query['conditions']['id'] = null;
			}
			return $query;
		}
		if (empty($results)) {
			return array();
		}
		$result = $results[0];
		if (!empty($result['Producer'])) {
			$result['Producer'] = $result['Producer'][0];
		}
		return $result;
	}
}
?>