<?php
/**
 * MenuHelper
 *
 * @Property HtmlHelper $Html
 * @Property SessionHelper $Session
 * @Property JavascriptHelper $Javascript
 * @Property JqueryHelper $Jquery
 * @Property TreeHelper $Tree
 */
class MenuHelper extends AppHelper {
/**
 * List of used helpers
 *
 * @var array
 * @access public
 */
	public $helpers = array('Html', 'Session', 'Javascript', 'Jquery', 'Tree');
/**
 * Model name for displayed categories
 *
 * @var string
 * @access protected
 */
	protected $_categoryModel = '';
/**
 * Controller for category links in menu
 *
 * @var string
 * @access protected
 */
	protected $_categoryController = '';
/**
 * List of scripts to execute in paqge bottom
 *
 * @var array
 * @access public
 */
	protected $_scripts = array();
/**
 * beforeRender callback
 *
 * @return void
 * @access public
 */
	public function beforeRender() {
		parent::beforeRender();
		$this->Jquery->uses('jquery', 'ui', 'potato.menu');
		$this->_categoryModel = $this->params['menu']['model'];
		$this->_categoryController = Inflector::underscore(Inflector::pluralize($this->_categoryModel));
	}
/**
 * Returns breadcrumbs markup
 *
 * @param string $id
 * @return string
 * @access public
 */
	public function breadcrumbs($id = 'breadcrumbs') {
		if ($this->here == '/') return '';
		$crumbs = $this->Html->getCrumbs('&nbsp;&raquo;&nbsp;', __('Main Page', true));
		return "<div id=\"{$id}\">{$crumbs}</div>\n";
	}
/**
 * Returns categories list for
 *
 * @param string $model
 * @return array
 * @access public
 */
	public function getCategories($model = null) {
		static $categories = null;

		if (is_null($categories)) {
			$key = 'menu.categories.list';
			$categories = Cache::read($key);
			if (empty($categories)) {
				$categories = $this->requestAction(array(
					'controller' => 'categories',
					'action' => 'menu',
					'admin' => false
				));
				Cache::write($key, $categories);
			}
		}
		if ($model && isset($categories[$model])) {
			return $categories[$model];
		}
		return $categories;
	}
/**
 * Returns JS for layout bottom
 *
 * @return string
 * @access public
 */
	public function javascript() {
		$isDebug = (bool) Configure::read();
		$script = "\$(function(){\n\t";
		$script .= join(ife($isDebug, "\n\t", ' '), $this->_scripts);
		$script .= "\n});";
		return $this->Javascript->codeBlock($script);
	}
/**
 * Returns root menu markup
 *
 * @param string $id
 * @return string
 * @access public
 */
	public function root($id = 'mainMenu') {
		$this->_scripts[] = '$("#' . $id . '").ptMenu();';
		$result = "<ul id=\"{$id}\" class=\"tabs\">\n"
			. "\t<li>" . $this->Html->link(__('Main Page', true), '/') . "</li>\n"
			. "\t<li>" . $this->Html->link(__('Catalogue', true), array('controller' => 'products', 'action' => 'index', 'admin' => false)) . "</li>\n"
			. "\t<li>" . $this->Html->link(__('Articles', true), array('controller' => 'articles', 'action' => 'index', 'admin' => false)) . "</li>\n"
			. "\t<li>" . $this->Html->link(__('About Us', true), array('controller' => 'pages', 'action' => 'display', 'about_us', 'admin' => false)) . "</li>\n";
		if (!$this->Session->read('Auth.User.Producer')) {
			$result .= "\t<li>" . $this->Html->link(__('Producer Registration', true), array('controller' => 'producers', 'action' => 'add', 'admin' => false)) . "</li>\n";
		}
		if ($this->Session->read('Auth.User.role') === 'admin') {
			$result .= "\t<li>" . $this->Html->link(__('Administration', true), '#') . "\n";
			$result .= "\t<ul>\n";
			$result .= "\t\t<li>" . $this->Html->link(__('Categories', true), array('controller' => 'categories', 'action' => 'index', 'admin' => true)) . "</li>\n";
			$result .= "\t\t<li>" . $this->Html->link(__('Producers', true), array('controller' => 'producers', 'action' => 'index', 'admin' => true)) . "</li>\n";
			$result .= "\t\t<li>" . $this->Html->link(__('Users', true), array('controller' => 'users', 'action' => 'index', 'admin' => true)) . "</li>\n";
			$result .= "\t</ul>\n";
			$result .= "\t</li>\n";
		}
		$result .= "</ul>\n";
		return $result;
	}
/**
 * Returns sidebar markup
 *
 * @param string $id
 * @return string
 * @access public
 */
	public function sidebar($id = 'subMenu') {
		$this->_scripts[] = '$("#' . $id . '").ptMenu({vertical:true});';
		$categories = $this->getCategories($this->_categoryModel);
		return $this->Tree->generate($categories, array('model' => 'Category', 'alias' => 'title', 'id' => $id, 'callback' => array(&$this, 'categoryLink')));
	}
/**
 * Callback for TreeHelper - returns menu link
 *
 * @param array $data
 * @return string
 * @access public
 */
	public function categoryLink($data) {
		return $this->Html->link($data['data']['Category']['title'], array(
			'controller' => $this->_categoryController,
			'action' => 'index',
			$data['data']['Category']['slug'],
			'admin' => false));
	}
}
?>