<?php
/**
 * Category model
 *
 * @property Article $Article
 * @property Product $Product
 */
class Category extends AppModel {
/**
 * List of used behaviors
 *
 * @var array
 * @access public
 */
	public $actsAs = array(
		'Owned',
		'Sluggable' => array(
			'length' => 72,
			'scope' => 'model'),
		'Tree'
	);
/**
 * List of hasMany associations
 *
 * @var array
 * @access public
 */
	public $hasMany = array(
		'Article' => array(
			'conditions' => array(
				'Category.model' => 'Article'),
			'dependent' => false),
		'Product' => array(
			'conditions' => array(
				'Category.model' => 'Product'),
			'dependent' => false)
	);
/**
 * validation rules
 *
 * @var array
 * @access public
 */
	public $validate = array(
		'model' => array(
			'required' => true,
			'allowEmpty' => false,
			'rule' => 'notEmpty'),
		'title' => array(
			'between' => array(
				'rule' => array('between', 4, 64),
				'last' => true),
			'isUnique' => array(
				'rule' => array('isUniqueInScope', 'title')))
	);
/**
 * Custom methods
 *
 * @var array
 * @access public
 */
	public $_findMethods = array('tree' => true);
/**
 * after Save callback
 *
 * @param boolean $created
 * @return void
 * @access public
 */
	public function afterSave($created) {
		parent::afterSave($created);
		$this->clearCache();
	}
/**
 * after Delete callback
 *
 * @return void
 * @access public
 */
	public function afterDelete() {
		parent::afterDelete();
		$this->clearCache();
	}
/**
 * clears cached list for menu
 *
 * @return void
 * @access public
 */
	public function clearCache() {
		Cache::delete('menu.categories.list');
	}
/**
 * Set tree scope
 *
 * @param string $scope
 * @return void
 * @access public
 */
	public function setScope($scope = '1 = 1') {
		if (strpos($scope, '=') === false) {
			$scope = array('Category.model' => $scope);
		}
		$this->Behaviors->Tree->settings['Category']['scope'] = $scope;
	}
/**
 * Custom find method for scoped tree
 *
 * @param string $state
 * @param array $query
 * @param array $data
 * @return array
 * @access protected
 */
	protected function _findTree($state, $query, $results = array()) {
		if ($state == 'before') {
			$query['fields'] = array(
				'id',
				'slug',
				'title',
				'lft',
				'rght');
			$query['conditions'] = $this->Behaviors->Tree->settings['Category']['scope'];
			$query['order'] = 'lft ASC';
			return $query;
		}
		return $results;
	}
}
?>