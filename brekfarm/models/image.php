<?php
/**
 * Image model
 *
 * @property Article $Article
 * @property Producer $Producer
 * @property Product $Product
 */
class Image extends AppModel {
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
		'Sortable'
	);
/**
 * List of belongsTo associations
 *
 * @var array
 * @access public
 */
	public $belongsTo = array(
		'Article' => array(
			'conditions' => array(
				'Image.model' => 'Article'),
			'foreignKey' => 'foreign_key'),
		'Producer' => array(
			'conditions' => array(
				'Image.model' => 'Producer'),
			'foreignKey' => 'foreign_key'),
		'Product' => array(
			'conditions' => array(
				'Image.model' => 'Product'),
			'foreignKey' => 'foreign_key')
	);
/**
 * Statuses
 *
 * @var array
 * @access public
 */
	public $statuses = array();
}
?>