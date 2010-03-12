<?php
/**
 * Producers Controller
 *
 * @property EmailComponent $Email
 * @property Producer $Producer
 */
class ProducersController extends AppController {
/**
 * List of used components
 *
 * @var array
 * @access public
 */
	public $components = array(
		'Email'
	);
/**
 * List of used helpers
 *
 * @var array
 * @access public
 */
	public $helpers = array('Form', 'Time');
/**
 * Pagination settings
 *
 * @var array
 * @access public
 */
	public $paginate = array(
		'fields' => array('id', 'slug', 'title', 'address', 'phone', 'email', 'url', 'client_code', 'status', 'created', 'modified', 'user_id'),
		'order' => array('Producer.status', 'Producer.created')
	);
/**
 * Auth map roles/actions
 *
 * @var array
 * @access protected
 */
	protected $_permissions = array(
		'edit' => '_userHasProducer',
		'email' => '_userHasProducer'
	);
/**
 * beforeFilter callback
 *
 * @return void
 * @access public
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('add', 'confirm', 'view');
	}
/**
 * Producer registration method
 *
 * @return void
 * @access public
 */
	public function add() {
		if (!$this->data) {
			$data = $this->Producer->create(array('user_id' => null));
			if ($id = $this->Auth->user('id')) {
				if ($this->Producer->hasAny(array('user_id' => $id))) {
//TODO flash, shield, redirect - and same check in validation, probably after U-P h1/hM/bT changes
					return;
				}
				$data['Producer']['user_id'] = $id;
			}
			$this->data = $data;
			return;
		}
		$email = $this->data['Producer']['email'];
		$fields = array('title', 'address', 'phone', 'email', 'url', 'description', 'user_id', 'promoter_id', 'tos');
		if ($this->Producer->save($this->data, true, $fields)) {
			if ($email) {
				$token = $this->Producer->tokens['email'];
				$host = $this->Email->host;
				$url = Router::url(array('action' => 'confirm', $token), true);
				$subject = sprintf(__('A new producer account at server %s', true), $host);
				if ($this->_sendEmail($email, $subject, 'producer_registration', compact('token', 'url', 'host'))) {
					$this->Shield->record('Producer Registration', null, 0, 'Producer', $this->Producer->id);
					$this->Session->setFlash(__('A new producer account has been created. You will receive an e-mail shortly to authenticate your account.', true));
				} else {
					$this->Producer->del();
					$log = serialize(array('error' => 'failed registration', 'email' => $email, 'smtpError' => $this->Email->smtpError));
					$this->Shield->record('Email Sending Failed', $log, 9, 'Producer');
					$this->Session->setFlash(__('A new producer account could not be created caused by temporary server problems. Try to register again in few minutes, please.', true));
				}
			} else {
				$this->Session->setFlash(__('A new producer account has been created. We will contact you soon to arrange the contract.', true));
			}
			$this->redirect('/');
		}
		$errors = $this->Producer->invalidFields();
		if (!empty($errors['email'])) {
			if ($errors['email'] == 'isUnique') {
				$this->Shield->record('Producer Registration With Duplicate Email', $email, 3, 'Producer');
			} elseif ($errors['email'] == 'isUniqueGlobally') {
				$this->Shield->record('Producer Registration With User Email', $email, 3, 'Producer');
			}
		}
	}
/**
 * Email token confirmation method
 *
 * @param string $value
 * @return void
 * @access public
 */
	public function confirm($value) {
		$id = $this->Producer->checkToken('email', $value);
		if (!$id) {
			$this->Shield->record('Invalid Token email', $value, 1, 'Producer');
			$this->Session->setFlash(__('This URL no longer exists.', true));
			$this->redirect('/');
		}
		$this->Producer->removeToken('email', $value);
		$this->Shield->record('Valid Token', 'email', 0, 'Producer');
		$this->Producer->id = $id;
		$status = $this->Producer->field('status');
		switch ($status) {
//			case 'new':
//				$this->Producer->setStatus('pending');
//				$this->Session->setFlash(__('Your email was validated. We will contact you soon to arrange the contract.', true));
//				break;
			default:
				$this->Producer->setStatus('ok');
				$this->Session->setFlash(__('Your email was validated.', true));
				break;
		}
		$this->redirect('/');
	}
/**
 * Producer edit
 *
 * @return void
 * @access public
 */
	public function edit() {
		$id = Set::extract('User.Producer.id', $this->Auth->user());
		$fields = array('slug', 'title', 'address', 'phone', 'url', 'description');
		if (empty($this->data)) {
			$this->data = $this->Producer->read($fields, $id);
			return;
		}
		$this->data['Producer']['id'] = $id;
		if ($this->Producer->save($this->data, true, array('address', 'phone', 'url', 'description'))) {
			$this->Session->setFlash(__('Producer account has been saved.', true));
			$this->redirect(array('action' => 'view', $this->data['Producer']['slug']));
		}
		unset($this->data['Producer']['id']);
	}
/**
 * Method for email change
 *
 * @return void
 * @access public
 */
	public function email() {
		$id = Set::extract('User.Producer.id', $this->Auth->user());
		$data = $this->Producer->read(array('slug', 'email', 'status'), $id);
		$viewUrl = array('action' => 'view', $data['Producer']['slug']);
		if ($data['Producer']['status'] !== 'ok') {
			$this->Shield->record('Wrong Status, Email Change Impossible', $data['Producer']['status'], 9, 'Producer', $id);
			$this->Session->setFlash(__('Impossible to change email of this account, it is waiting for another confirmation.', true));
			$this->redirect($viewUrl);
		}
		if (empty($this->data)) {
			$this->data = $data;
			return;
		}
		$this->data['Producer']['id'] = $id;
		if ($this->Producer->save($this->data, true, array('email'))) {
			if (! (bool) ($token = $this->Producer->createToken('email'))) {
				$this->data['Producer']['email'] = $data['Producer']['email'];
				$this->Producer->save($this->data, false, array('email'));
				$this->Shield->record('Email Change Token Creation Failed', $this->data['Producer']['email'], 1, 'Producer', $id);
				$this->Session->setFlash(__('Impossible to change email of this account, it is waiting for another confirmation.', true));
				$this->redirect($viewUrl);
			}
			$email = $this->data['Producer']['email'];
			$host = $this->Email->host;
			$url = Router::url(array('action' => 'confirm', $token), true);
			$subject = sprintf(__('Request for email change at server %s', true), $host);
			if ($this->_sendEmail($email, $subject, 'producer_email', compact('token', 'url', 'host'))) {
				$this->Producer->setStatus('email');
				$this->Shield->record('Email Change', $email, 1, 'Producer', $id);
				$this->Session->setFlash(__('Producers email has been changed. You should receive an e-mail shortly with confirmation link.', true));
			} else {
				$this->data['Producer']['email'] = $data['Producer']['email'];
				$this->Producer->save($this->data, false, array('email'));
				$log = serialize(array('error' => 'failed registration', 'email' => $email, 'smtpError' => $this->Email->smtpError));
				$this->Shield->record('Email Sending Failed', $log, 9, 'Producer');
				$this->Session->setFlash(__('Producers email could not be changed because of temporary server problems. Try it again in few minutes, please.', true));
			}
			$this->redirect($viewUrl);
		}
		unset($this->data['Producer']['id']);
		$errors = $this->Producer->invalidFields();
		if (!empty($errors['email'])) {
			if ($errors['email'] == 'isUnique') {
				$this->Shield->record('Email Change To Duplicate Email', $this->data['Producer']['email'], 3, 'Producer');
			} elseif ($errors['email'] == 'isUniqueGlobally') {
				$this->Shield->record('Email Change To Unallowed User Email', $this->data['Producer']['email'], 3, 'Producer');
			}
		}
	}
/**
 * Method for producer detail
 *
 * @param string $slug
 * @return void
 * @access public
 */
	public function view($slug) {
		$conditions = compact('slug');
		$isOwner = $this->_userIsOwner();
		if (!$isOwner) {
			$conditions['status'] = array('ok', 'email', 'delayed');
		}
		$fields = array('slug', 'title', 'address', 'phone', 'email', 'url', 'description', 'client_code', 'user_id', 'status', 'created');
		if ($producer = $this->Producer->find('first', compact('conditions', 'fields'))) {
			return $this->set(compact('isOwner', 'producer'));
		}
		$this->cakeError('error404');
	}
/**
 * Administration list of producers
 *
 * @return void
 * @access public
 */
	public function admin_index() {
		$this->set('producers', $this->paginate());
	}
/**
 * Method for allowing denied producers
 *
 * @param string $id
 * @return void
 * @access public
 */
        public function admin_allow($id) {
            $producer = $this->Producer->read(array('status'), $id);
            if (empty($producer) || $producer['Producer']['status'] != 'denied') {
                $this->Session->setFlash(__('Invalid Producer.', true));
            } else {
                $this->Producer->setStatus('ok');
            }
            $this->redirect($this->referer());
        }
/**
 * Method for denying producers
 *
 * @param string $id
 * @return void
 * @access public
 */
        public function admin_deny($id) {
            $producer = $this->Producer->read(array('status'), $id);
            if (empty($producer) || $producer['Producer']['status'] == 'denied') {
                $this->Session->setFlash(__('Invalid Producer.', true));
            } else {
                $this->Producer->setStatus('denied');
            }
            $this->redirect($this->referer());
        }
/**
 * Method for attaching existing user account to producer
 *
 * @param string $id
 * @return void
 * @access public
 */
	public function admin_owner($id) {
		if (empty($this->data)) {
			$referer = $this->referer();
			$this->data = $this->Producer->read(array('id', 'user_id'), $id);
			if (empty($this->data['Producer']['id'])) {
				$this->Session->setFlash(__('Invalid Producer.', true));
				$this->redirect($referer);
			} elseif (!empty($this->data['Producer']['user_id'])) {
				$this->Session->setFlash(__('This producer already have an owner.', true));
				$this->redirect($referer);
			}
			$this->Session->write('PAReferer', $referer);
			return;
		}
		$this->Producer->create($this->data);
		if ($this->Producer->setOwner()) {
			$this->Session->setFlash(__('Owner of producer has been set.', true));
			$this->redirect($this->Session->read('PAReferer'));
		}
	}
/**
 * Authorization check
 *
 * @return boolean
 * @access protected
 */
	protected function _userIsOwner() {
		if (empty($this->passedArgs) || !isset($this->passedArgs['0'])) {
			return false;
		}
		$slug = $this->passedArgs['0'];
		$user_id = $this->Producer->field('user_id', compact('slug'));
		return ($user_id && $user_id == $this->Auth->user('id'));
	}
/**
 * Authorization check
 *
 * @return boolean
 * @access protected
 */
	protected function _userHasProducer() {
		return (bool) $this->Auth->user('Producer');
	}
}
?>