<?php
/**
 * Users Controller
 *
 * @property EmailComponent $Email
 * @property User $User
 */
class UsersController extends AppController {
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
	public $helpers = array('Form', 'Time', 'Gravatar', 'Jquery');
/**
 * Pagination settings
 *
 * @var array
 * @access public
 */
	public $paginate = array(
		'fields' => array('id', 'username', 'email', 'promo_code', 'promo_rate', 'status', 'role', 'created', 'modified'),
		'order' => array('User.status', 'User.created')
	);
/**
 * Auth map roles/actions
 *
 * @var array
 * @access protected
 */
	protected $_permissions = array(
		'edit' => '*',
		'account' => '*',
		'home' => '*'
	);
/**
 * beforeFilter callback
 *
 * @return void
 * @access public
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('add', 'confirm', 'login', 'logout', 'reset');
		if (in_array($this->action, array('add', 'account'))) {
			$this->Auth->authenticate = $this->User;
		}
	}
/**
 * beforeRender callback
 *
 * @return void
 * @access public
 */
	public function beforeRender() {
		parent::beforeRender();
		if (in_array($this->action, array('add', 'edit', 'home'))) {
			$this->set('paymentMethods', array(
				'bank' => __('Bank Account Transfer', true),
				'cheque' => __('Post Office Remittance', true),
			));
		}
	}
/**
 * Sign Up method
 *
 * @return void
 * @access public
 */
	public function add() {
		if (!$this->data) {
			$this->data = $this->User->create();
			return;
		}
		$email = $this->data['User']['email'];
		if ($this->User->save($this->data, true, array('username', 'email', 'tos', 'passwd', 'name', 'payment_method', 'payment_target'))) {
			$token = $this->User->tokens['email'];
			$host = $this->Email->host;
			$url = Router::url(array('action' => 'confirm', 'email', $token), true);
			$subject = sprintf(__('A new user account at server %s', true), $host);
			if ($this->_sendEmail($email, $subject, 'user_registration', compact('token', 'url', 'host'))) {
				$this->Shield->record('User Registration', null, 0, 'User', $this->User->id);
				$this->Session->setFlash(__('Your account has been created. You will receive an e-mail shortly to authenticate your account. Once validated, you will be able to login.', true));
			} else {
				$this->User->del();
				$log = serialize(array('error' => 'failed registration', 'email' => $email, 'smtpError' => $this->Email->smtpError));
				$this->Shield->record('Email Sending Failed', $log, 9, 'User');
				$this->Session->setFlash(__('A new account could not be created caused by temporary server problems. Try to register again in few minutes, please.', true));
			}
			$this->redirect('/');
		}
		$errors = $this->User->invalidFields();
		if (!empty($errors['email'])) {
			if ($errors['email'] == 'isUnique') {
				$this->Shield->record('User Registration With Duplicate Email', $email, 3, 'User');
			} elseif ($errors['email'] == 'isUniqueGlobally') {
				$this->Shield->record('User Registration With Producer Email', $email, 3, 'User');
			}
		}
	}
/**
 * Tokens confirmation method
 *
 * @param string $type
 * @param string $value
 * @return void
 * @access public
 */
	public function confirm($type, $value) {
		$id = $this->User->checkToken($type, $value);
		if (!$id) {
			$this->Shield->record('Invalid Token ' . $type, $value, 1, 'User');
			$this->Session->setFlash(__('This URL no longer exists.', true));
			$this->redirect('/');
		}
		$this->User->removeToken($type, $value);
		$this->Shield->record('Valid Token', $type, 0, 'User');
		$this->User->id = $id;
		$redirect = '/';
		switch ($type) {
			case 'email':
				$this->User->setStatus('ok');
				$this->Session->setFlash(__('Your email was validated.', true));
				$redirect = array('action' => $this->Auth->user() ? 'home' : 'login');
				break;
			case 'passwd':
				$current = $this->User->field('status');
				if ($current !== 'ok') {
					$this->Shield->record('Wrong Status, Passwd Reset Impossible', $current, 9, 'User', $this->User->id);
					$this->Session->setFlash(__('It is impossible to change password of this account, it is waiting for another confirmation.', true));
					$this->redirect($redirect);
				}
				$password = $this->User->generateToken();
				$host = $this->Email->host;
				$email = $this->User->field('email');
				$subject = sprintf(__('Password reset at server %s', true), $host);
				if ($this->_sendEmail($email, $subject, 'user_password_reset', compact('password', 'host'))) {
					$this->User->saveField('passwd', $password, false);
					$this->Shield->record('Random Pasword Sent', $email, 1, 'User', $this->User->id);
					$this->Session->setFlash(__('You will receive an e-mail with the temporary password shortly.', true));
				} else {
					$log = serialize(array('error' => 'failed reset password', 'email' => $email, 'smtpError' => $this->Email->smtpError));
					$this->Shield->record('Email Sending Failed', $log, 9, 'User');
					$this->Session->setFlash(__('Password could not be changed caused by temporary server problems. Try it again in few minutes, please.', true));
				}
				break;
		}
		$this->redirect($redirect);
	}
/**
 * Account edit
 *
 * @return void
 * @access public
 */
	public function edit() {
		$fields = array('name', 'payment_method', 'payment_target');
		if (empty($this->data)) {
			$this->data = $this->User->read($fields, $this->Auth->user('id'));
			return;
		}
		$this->data['User']['id'] = $this->Auth->user('id');
		if ($this->User->save($this->data, true, $fields)) {
			$this->Session->setFlash(__('Your profile has been saved.', true));
			$this->redirect(array('action' => 'home'));
		}
		unset($this->data['User']['id']);
	}
/**
 * Homepage of registered user
 *
 * @return void
 * @access public
 */
	public function home() {
		$this->User->id = $this->Auth->user('id');
		$this->set('user', $this->User->find('home'));
	}
/**
 * Sign In method
 *
 * @return void
 * @access public
 */
	public function login() {
		if (!$this->data) {
			if ($this->Auth->user()) {
				$this->Auth->logout();
			}
			return;
		}
		if ($user = $this->Auth->user()) {
			$this->_setAuthSession();
			$this->Session->setFlash(sprintf(__('Now you are logged in, %s!', true), $user['User']['username']));
			$this->Shield->record('Login', $user['User']['email'], 0, 'User');
			if (!empty($this->data['User']['remember_me'])) {
				$cookie = array();
				$cookie['username'] = $this->data['User']['username'];
				$cookie['passwd'] = $this->data['User']['passwd'];
				$this->Cookie->write('gate', $cookie, true, '+2 weeks');
			}
			$this->redirect($this->Auth->loginRedirect);
		} else {
			$this->Shield->record('Wrong Credentials Login', $this->data['User']['username'], 2, 'User');
		}
	}
/**
 * Sign Out method
 *
 * @return void
 * @access public
 */
	public function logout() {
		$this->Cookie->delete('gate');
		$this->Session->setFlash(__('You were logged out.', true));
		$this->redirect($this->Auth->logout());
	}
/**
 * Method for forgotten passwords
 *
 * @return void
 * @access public
 */
	public function reset() {
		if (!$this->data) {
			return;
		}
		$this->User->create($this->data);
		$errors = $this->User->invalidFields();
		$email = $this->data['User']['email'];
		$flash = __('You will receive an e-mail with the description how to change your password shortly.', true);
		if (empty($errors['email'])) {
			$this->Shield->record('Change Password of Nonexisting Account Impossible', $email, 9, 'User');
			$this->Session->setFlash($flash);
			$this->redirect('/');
		} elseif ($errors['email'] === 'isUnique') {
			$this->User->id = $this->User->field('id', array('email' => $email));
			$current = $this->User->field('status');
			if ($current !== 'ok') {
				$this->Shield->record('Wrong Status, Password Reset Impossible', $current, 9, 'User', $this->User->id);
				$this->Session->setFlash($flash);
				$this->redirect('/');
			}
			if (! (bool) ($token = $this->User->createToken('passwd'))) {
				$this->Shield->record('Password Reset Token Creation Failed', $current, 1, 'User', $this->User->id);
				$this->Session->setFlash($flash);
				$this->redirect('/');
			}
			$host = $this->Email->host;
			$url = Router::url(array('action' => 'confirm', 'passwd', $token), true);
			$subject = sprintf(__('Request for password reset at server %s', true), $host);
			if ($this->_sendEmail($email, $subject, 'user_password_init', compact('token', 'url', 'host'))) {
				$this->Shield->record('Pasword Reset Token Sent', $email, 2, 'User', $this->User->id);
				$this->Session->setFlash($flash);
			} else {
				$this->User->removeToken('passwd', $token);
				$log = serialize(array('error' => 'failed reset password init', 'email' => $email, 'smtpError' => $this->Email->smtpError));
				$this->Shield->record('Email Sending Failed', $log, 9, 'User');
				$this->Session->setFlash(__('Password could not changed because of temporary server problems. Try it again in few minutes, please.', true));
			}
			$this->redirect('/');
		}
	}
/**
 * Administration list of users
 *
 * @return void
 * @access public
 */
	public function admin_index() {
		$this->set('users', $this->paginate());
	}
/**
 * Change email
 *
 * @return mixed
 * @access protected
 */
 	protected function _email() {
		if ($this->User->save($this->data, true, array('email'))) {
			$user = $this->Auth->user();
			$id = $user['User']['id'];
			if (! (bool) ($token = $this->User->createToken('email'))) {
				$this->data['User']['email'] = $user['User']['email'];
				$this->User->save($this->data, false, array('email'));
				$this->Shield->record('Email Change Token Creation Failed', $this->data['User']['email'], 1, 'User', $id);
				$this->Session->setFlash(__('It is impossible to change email of this account, it is waiting for another confirmation.', true));
				return array('action' => 'home');
			}
			$email = $this->data['User']['email'];
			$host = $this->Email->host;
			$url = Router::url(array('action' => 'confirm', 'email', $token), true);
			$subject = sprintf(__('Request for email change at server %s', true), $host);
			if ($this->_sendEmail($email, $subject, 'user_email', compact('token', 'url', 'host'))) {
				$this->User->setStatus('email');
				$this->Session->write('Auth.User.email', $this->data['User']['email']);
				$this->Shield->record('Email Change', $email, 1, 'User', $id);
				$this->Session->setFlash(__('Your email has been changed. You will receive an e-mail with the confirmation link shortly.', true));
			} else {
				$this->data['User']['email'] = $user['User']['email'];
				$this->User->save($this->data, false, array('email'));
				$log = serialize(array('error' => 'failed registration', 'email' => $email, 'smtpError' => $this->Email->smtpError));
				$this->Shield->record('Email Sending Failed', $log, 9, 'User');
				$this->Session->setFlash(__('Your email could not be changed caused by temporary server problems. Try it again in few minutes, please.', true));
			}
			return array('action' => 'home');
		}
		return null;
	}
/**
 * Change password
 *
 * @return mixed
 * @access protected
 */
	protected function _password() {
		if ($this->User->save($this->data, true, array('passwd'))) {
			$this->Cookie->delete('gate');
			$this->Session->setFlash(__('Your password has been saved.', true), 'default', array(), 'auth');
			return array('action' => 'home');
		}
		return null;
	}
/**
 * Method for email and password change
 *
 * @return void
 * @access public
 */
	public function account() {
		$user = $this->Auth->user();
		$id = $user['User']['id'];
		$status = $user['User']['status'];
		if ($status !== 'ok') {
			$this->Shield->record('Wrong Status, Account Change Impossible', $status, 9, 'User', $id);
			$this->Session->setFlash(__('Impossible to change email or password of this account, it is waiting for another confirmation.', true));
			$this->redirect(array('action' => 'home'));
		}
		$data = $this->User->read(array('email', 'passwd'), $id);
		if (empty($this->data)) {
			unset($data['User']['passwd']);
			$this->data = $data;
			return;
		}
		$this->data['User']['id'] = $id;
		$hash = empty($this->data['User']['passwd_old']) ? '' : Security::hash($this->data['User']['passwd_old'], null, true);
		if ($hash !== $data['User']['passwd']) {
			$this->User->invalidate('passwd_old');
			unset($this->data['User']['id']);
			unset($this->data['User']['passwd_old']);
			return;
		}
		$redirect = null;
		if (!empty($this->data['User']['passwd']) && $this->data['User']['passwd'] != $this->data['User']['passwd_old']) {
			$redirect = $this->_password();
		}
		$this->User->create($this->data);
		if ($data['User']['email'] != $this->data['User']['email']) {
			$redirect = $this->_email();
		}
		if ($redirect) {
			$this->redirect($redirect);
		}
		unset($this->data['User']['id']);
		unset($this->data['User']['passwd_old']);
		$errors = $this->User->invalidFields();
		if (empty($errors)) {
			$this->Session->setFlash(__('Nothing was saved.', true));
		} elseif (!empty($errors['email'])) {
			if ($errors['email'] == 'isUnique') {
				$this->Shield->record('Email Change To Duplicate Email', $this->data['User']['email'], 3, 'User');
			} elseif ($errors['email'] == 'isUniqueGlobally') {
				$this->Shield->record('Email Change To Unallowed Producer Email', $this->data['User']['email'], 3, 'User');
			}
		}
	}
}
?>