<?php
/**
 * Product model
 *
 * @property Category $Category
 * @property Image $Image
 * @property Producer $Producer
 */
class Product extends AppModel {
/**
 * List of used behaviors
 *
 * @var array
 * @access public
 */
	public $actsAs = array(
		'Status' => array(
			'status' => array(
				'draft' => array(		// hidden from unauthorized
					'published',
					'denied'),
				'published' => array(	// public
					'draft',
					'denied'),
				'denied' => array(		// hidden and locked for changes
					'draft',
					'published'))),
		'Owned',
		'Pictorial',
		'Sortable'
	);
/**
 * List of belongsTo associations
 *
 * @var array
 * @access public
 */
	public $belongsTo = array(
		'Category',
		'Producer'
	);
/**
 * List of hasMany associations
 *
 * @var array
 * @access public
 */
	public $hasMany = array(
		'Image' => array(
			'conditions' => array(
				'Image.model' => 'Product'),
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
		'description' => array(
			'rule' => 'notEmpty'),
		'price' => array(
			'rule' => 'numeric'),
		'unit' => array(
			'rule' => 'notEmpty'),
		'category_id' => array(
			'rule' => 'notEmpty'),
		'producer_id' => array(
			'rule' => 'notEmpty')
	);
/**
 * Statuses
 *
 * @var array
 * @access public
 */
	public $statuses = array();
/**
 * after Save callback
 *
 * @param boolean $created
 * @return void
 * @access public
 */
	public function afterSave($created) {
		parent::afterSave($created);
		$this->Category->clearCache();
	}
/**
 * after Delete callback
 *
 * @return void
 * @access public
 */
	public function afterDelete() {
		parent::afterDelete();
		$this->Category->clearCache();
	}
/**
 * beforeValidate callback
 *
 * @param array $options
 * @return boolean
 * @access public
 * @todo implement check of approved_from/approved_to
 */
	public function beforeValidate($options = array()) {
		if (!parent::beforeValidate($options)) return false;
		return true;
	}
}
?>