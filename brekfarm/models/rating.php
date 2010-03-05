<?php
/**
 * Rating model
 *
 * @property Producer $Producer
 * @property User $User
 */
class Rating extends AppModel {
/**
 * List of used behaviors
 *
 * @var array
 * @access public
 */
	public $actsAs = array(
		'Status' => array(
			'status' => array(
				'clean' => array(	// marked as clean by antispam system
					'dirty'),
				'spam' => array(		// marked as spam by antispam system
					'ham'),
				'dirty' => array(	// marked as spam by hand
					'ham'),
				'ham' => array(		// marked as legitimate content by hand
					'dirty'))),
		'Owned',
		'SpamDetector'
	);
/**
 * List of belongsTo associations
 *
 * @var array
 * @access public
 */
	public $belongsTo = array(
		'Producer' => array(
			'conditions' => array(
				'Rating.model' => 'Producer'),
			'foreignKey' => 'foreign_key',
			'ratingCache' => true,
			'ratingScope' => array(
				'Rating.model' => 'Producer',
				'Rating.status' => array('clean', 'ham'))),
		'User' => array(
			'foreignKey' => 'created_by')
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