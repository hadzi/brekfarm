<?php
/**
 * App-wide parent class for all controllers
 *
 * @property AuthComponent $Auth
 * @property CookieComponent $Cookie
 * @property RequestHandlerComponent $RequestHandler
 * @property SecurityComponent $Security
 * @property SessionComponent $Session
 * @property Shield $Shield
 * @property ToolbarComponent $Toolbar
 */
class AppController extends Controller {
/**
 * List of used components
 *
 * @var array
 * @access public
 */
	public $components = array(
		'Session',
		'Security',
		'RequestHandler',
		'Auth',
		'Cookie' => array(
			'name' => 'brek',
			'time' => '+14 days')
	);
/**
 * List of used helpers
 *
 * @var array
 * @access public
 */
	public $helpers = array('Html', 'Session', 'Javascript', 'Jquery', 'Tree', 'Menu');
/**
 * List of used models
 *
 * @var array
 * @access public
 */
	public $uses = array('Shield');
/**
 * Auth map roles/actions
 *
 * @var array
 * @access protected
 */
	protected $_permissions = array();
/**
 * beforeFilter callback
 *
 * @return void
 * @access public
 */
	public function beforeFilter() {
		$this->__initAuth();
		$this->__initShield();
		$this->__autoLogin();
		$this->_resetEmail();
		if (!empty($this->data)) {
			ignore_user_abort(true);
		}
		if (!empty($this->params['requested'])) {
			$this->Auth->allow($this->action);
		}
		$this->params['menu'] = array(
			'model' => 'Product',
			'category' => null
		);
	}
/**
 * beforeRender callback
 *
 * @return void
 * @access public
 */
	public function beforeRender() {
		if (!empty($this->params['admin'])) {
			$this->layout = 'admin';
		}
		$this->set('bottom_for_layout', '');
	}
/**
 * Common isAuthorized() callback for AuthComponent
 *
 * Parses AppController::$_permissions
 *
 * @return boolean
 * @access public
 */
	public function isAuthorized() {
		$role = $this->Auth->user('role');
		if ($role === 'admin') return true;
		if (empty($this->_permissions[$this->action])) return false;
		$perms = $this->_permissions[$this->action];
		if (is_string($perms)) {
			if ($perms === '*') return true;
			return (bool) call_user_func(array(&$this, $perms));
		}
		if ($role) return in_array($role, $perms);
		return false;
	}
/**
 * Prepares necessary (and only necessary) session data for AuthComponent
 *
 * @return void
 * @access protected
 */
	protected function _setAuthSession() {
		$user = $this->Auth->user();
		$fields = array('id', 'slug', 'title');
		$conditions = array('user_id' => $user['User']['id']);
		if ($Producer = ClassRegistry::init('Producer')->find('first', compact('fields', 'conditions'))) {
			$Producer = $Producer['Producer'];
		}
		extract($user['User']);
		$this->Session->write('Auth.User', compact('id', 'username', 'email', 'status', 'role', 'Producer'));
	}
/**
 * reset EmailComponent for new email message
 *
 * @param array $config
 * @return void
 * @access protected
 */
	protected function _resetEmail($config = array()) {
		if (!isset($this->Email)) {
			return;
		}
		$config = array_merge(Configure::read('Config.email'), $config);
		$this->Email->reset();
		foreach ($config as $key => $value) {
			$this->Email->{$key} = $value;
		}
	}
/**
 * Wrapper for sending emails
 *
 * @param string $email
 * @param string $subject
 * @param string $template
 * @param array $data
 * @param array $config
 * @return void
 * @access protected
 */
	protected function _sendEmail($email, $subject, $template = 'default', $data = array(), $config = array()) {
		$this->_resetEmail($config);
		if ($data) {
			$this->set($data);
		}
		$this->Email->to = $email;
		$this->pageTitle = $this->Email->subject = $subject;
		return $this->Email->send(null, $template);
	}
/**
 * Attempts to login from cookie
 *
 * @return void
 * @access private
 */
	private function __autoLogin() {
		if (!$this->data && !$this->Auth->user() && ($cookie = $this->Cookie->read('gate'))) {
			if ($this->Auth->login($cookie)) {
				$this->_setAuthSession();
				$this->Shield->record('Autologin', $this->Auth->user('email'), 0, 'User');
			} else {
				$this->Shield->record('Invalid Autologin Cookie', $cookie['username'], 5, 'User');
				$this->Cookie->delete('gate');
			}
		}
	}
/**
 * AuthComponent initialization
 *
 * @return void
 * @access private
 */
	private function __initAuth() {
		$this->Auth->autoRedirect = false;
		$this->Auth->authorize = 'controller';
		$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login', 'admin' => false);
		$this->Auth->loginRedirect = array('controller' => 'users', 'action' => 'home', 'admin' => false);
		$this->Auth->logoutRedirect = '/';
		$this->Auth->fields = array('username' => 'username', 'password' => 'passwd');
		$this->Auth->userScope = array(
			'User.tos' => true,
			'User.status' => array('ok', 'email'));
		$this->Auth->loginError = __('Invalid username and password combination. Please, try again.', true);
		$this->Auth->authError = __('You are not authorized to access that page.', true);
	}
/**
 * Shield model initialization
 *
 * @return void
 * @access private
 */
	private function __initShield() {
		$this->Shield->request = array(
			'ip' => $this->RequestHandler->getClientIP(),
			'here' => $this->here,
			'user_agent' => (string) env('HTTP_USER_AGENT'),
			'referrer' => $this->RequestHandler->getReferer(),
			'referer' => $this->referer()
		);
	}
}
?>