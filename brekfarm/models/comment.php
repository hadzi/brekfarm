<?php
/**
 * Comment model
 *
 * @property Article $Article
 * @property Producer $Producer
 * @property User $User
 */
class Comment extends AppModel {
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
		'Article' => array(
			'conditions' => array(
				'Comment.model' => 'Article'),
			'foreignKey' => 'foreign_key'),
		'Producer' => array(
			'conditions' => array(
				'Comment.model' => 'Producer'),
			'foreignKey' => 'foreign_key'),
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