<?php
/**
 * Article model
 *
 * @property Category $Category
 * @property Comment $Comment
 * @property Image $Image
 */
class Article extends AppModel {
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
		'Commentable',
		'Owned',
		'Pictorial',
		'Sluggable' => array(
			'length' => 146,
			'scope' => 'category_id')
	);
/**
 * List of belongsTo associations
 *
 * @var array
 * @access public
 */
	public $belongsTo = array(
		'Category'
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
				'Comment.model' => 'Article'),
			'foreignKey' => 'foreign_key',
			'dependent' => true,
			'counterCache' => true,
			'counterScope' => array(
				'Comment.model' => 'Article',
				'Comment.status' => array('clean', 'ham'))),
		'Image' => array(
			'conditions' => array(
				'Image.model' => 'Article'),
			'foreignKey' => 'foreign_key',
			'dependent' => true)
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
}
?>