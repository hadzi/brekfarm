<?php
/**
 * Producer model
 *
 * @property Comment $Comment
 * @property Favourite $Favourite
 * @property Geocode $Geocode
 * @property Image $Image
 * @property Invoice $Invoice
 * @property Product $Product
 * @property Rating $Rating
 * @property User $User
 */
class Producer extends AppModel {
/**
 * List of used behaviors
 *
 * @var array
 * @access public
 */
	public $actsAs = array(
		'Status' => array(
			'status' => array(
				'new' => array(			// waiting for email token verification, generates payment (and refund if necessary) with status 'new' and null price
					'ok',// IMPORTANT this status path (new->ok) was added for project start only
					'pending',
					'denied'),
				'pending' => array(		// producer account waiting for first invoice
					'invoiced',
					'denied'),
				'invoiced' => array(	// invoice sent, changes existing payment status to 'sent', or generates payment (and refund if necessary) with status 'new'
					'ok',
					'delayed',
					'denied'),
				'ok' => array(			// money received, changes payment status to 'paid'
					'email',
					'passwd',
					'invoiced',
					'denied'),
				'email' => array(		// waiting for email-change token verification, possible only in 'ok' state
					'ok',
					'denied'),
				'delayed' => array(		// invoice after term, changes payment status to 'delayed'
					'ok',
					'denied'),
				'denied' => array(		// unpaid or cancelled invoice, changes payment status to 'unpaid'
					'invoiced',
					'ok'))),
		'Commentable',
		'Geocoded',
		'Owned',
		'Pictorial',
		'Ratable',
		'Sluggable' => array(
			'length' => 146),
		'Sortable',
		'SpamDetector',
		'Tokenable' => array(
			'field' => 'client_code',
			'length' => 7,
			'possible' => '0123456789',
			'notStartWith' => '0'),
		'Mailable'
	);
/**
 * List of belongsTo associations
 *
 * @var array
 * @access public
 */
	public $belongsTo = array(
		'Geocode',
		'User'
	);
/**
 * List of hasMany associations
 *
 * @var array
 * @access public
 */
	public $hasMany = array(
		'Comment' => array(
			'conditions' => array(
				'Comment.model' => 'Producer'),
			'foreignKey' => 'foreign_key',
			'dependent' => true,
			'counterCache' => true,
			'counterScope' => array(
				'Comment.model' => 'Producer',
				'Comment.status' => array('clean', 'ham'))),
		'Favourite' => array(
			'conditions' => array(
				'Favourite.model' => 'Producer'),
			'foreignKey' => 'foreign_key',
			'dependent' => true),
		'Image' => array(
			'conditions' => array(
				'Image.model' => 'Producer'),
			'foreignKey' => 'foreign_key',
			'dependent' => true),
		'Invoice' => array(
			'conditions' => array(
				'Invoice.model' => 'Producer'),
			'foreignKey' => 'foreign_key',
			'dependent' => true),
		'Product' => array(
			'dependent' => true),
		'Rating' => array(
			'conditions' => array(
				'Rating.model' => 'Producer'),
			'foreignKey' => 'foreign_key',
			'dependent' => true,
			'ratingCache' => true,
			'ratingScope' => array(
				'Rating.model' => 'Producer',
				'Rating.status' => array('clean', 'ham'))),
		'Token' => array(
			'conditions' => array(
				'Token.model' => 'Producer'),
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
		'title' => array(
			'between' => array(
				'rule' => array('between', 6, 128),
				'last' => true),
			'isUnique' => 'isUnique'),
		'address' => array(
			'rule' => 'notEmpty'),
		'phone' => array(
			'format' => array(
				'rule' => '/^\d{9}$/',
				'last' => true),
			'isUnique' => 'isUnique'),
		'email' => array(
			'maxLength' => array(
				'required' => false,
				'allowEmpty' => true,
				'rule' => array('maxLength', 128),
				'last' => true),
			'format' => array(
				'required' => false,
				'allowEmpty' => true,
				'rule' => array('email'),
				'last' => true),
			'server' => array(
				'required' => false,
				'allowEmpty' => true,
				'rule' => array('email', true),
				'last' => true),
			'isUnique' => 'isUnique',
			'isUniqueGlobally' => 'isUniqueEmail'),
		'url' => array(
			'required' => false,
			'allowEmpty' => true,
			'rule' => array('url')),
		'description' => array(
			'rule' => 'notEmpty'),
		'tos' => array(
			'allowEmpty' => false,
			'rule' => array('custom', '[1]')),
		'promo_code' => array(
			'selfJoin' => array(
				'required' => false,
				'allowEmpty' => true,
				'rule' => 'isNotOwnPromoCode'),
			'notExists' => array(
				'required' => false,
				'allowEmpty' => true,
				'rule' => 'isValidPromoCode'))
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
 * beforeSave callback
 *
 * @param array $options
 * @return boolean
 * @access public
 */
	public function beforeSave($options = array()) {
		if (!parent::beforeSave($options)) return false;
		if (!$this->exists()) {
			if (!empty($this->data[$this->alias]['promo_code'])) {
				$conditions = array('promo_code' => $this->data[$this->alias]['promo_code']);
				$this->data[$this->alias]['promoter_id'] = $this->User->field('id', $conditions);
			}
			if (empty($this->data[$this->alias]['email'])) {
				$this->data[$this->alias]['status'] = 'pending';
			}
		}
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
			if (!empty($this->data[$this->alias]['email'])) {
				$this->createToken('email');
			}
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
		foreach (array('email', 'url', 'user_id', 'promoter_id') as $field) {
			if (isset($this->data[$this->alias][$field]) && empty($this->data[$this->alias][$field])) {
				$this->data[$this->alias][$field] = null;
			}
		}
		return true;
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
		$id = $this->exists() ? $this->field('user_id') : (empty($this->data[$this->alias]['user_id']) ? null : $this->data[$this->alias]['user_id']);
		if ($id) {
			$conditions['id !='] = $id;
		}
		return ! $this->User->hasAny($conditions);
	}
/**
 * custom validation to check promo code
 *
 * @param array $data
 * @return boolean
 * @access public
 */
	public function isValidPromoCode($data) {
		return $this->User->hasAny(array('promo_code' => $data['promo_code']));
	}
/**
 * custom validation to check promo code
 *
 * @param array $data
 * @return boolean
 * @access public
 */
	public function isNotOwnPromoCode($data) {
		if ($id = Set::extract($_SESSION, 'Auth.User.id')) {
			return ($data['promo_code'] != $this->User->field('promo_code', compact('id')));
		}
		return true;
	}
/**
 * Method for attaching existing user account to producer
 *
 * @param string $email
 * @return mixed
 * @access public
 */
	public function setOwner($email = null) {
		if (!$this->exists()) {
			return null;
		}
		$user_id = $this->field('user_id');
		if ($user_id) {
			return null;
		}
		$email = empty($email) ? (empty($this->data['User']['email']) ? null : $this->data['User']['email']) : $email;
		if (!$email) {
			$this->User->invalidate('email', 'notEmpty');
		} elseif ($user_id = $this->User->field('id', compact('email'))) {
			if ($this->hasAny(compact('user_id'))) {
				$this->User->invalidate('email', 'hasProducer');
			} elseif ($user_id == $this->field('promoter_id')) {
				$this->User->invalidate('email', 'isPromoter');
			} else {
				return $this->saveField('user_id', $user_id, false);
			}
		} else {
			$this->User->invalidate('email', 'invalidOrUnused');
		}
		return false;
	}
}
?>