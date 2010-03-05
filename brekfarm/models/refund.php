<?php
/**
 * Refund model
 *
 * @property Invoice $Invoice
 * @property User $User
 */
class Refund extends AppModel {
/**
 * Custom database table name
 *
 * @var string
 * @access public
 */
	public $useTable = 'payments';
/**
 * List of used behaviors
 *
 * @var array
 * @access public
 */
	public $actsAs = array(
		'Status' => array(
			'status' => array(
				'new' => array(				// new refund record waiting for payment from producer, caused by producer.new or producer.reinvoiced
					'progress',
					'paid',
					'cancelled'),
				'progress' => array(		// money are confirmed, refund is being set up, caused by invoice.paid
					'paid',
					'cancelled'),
				'paid' => array(			// money sent to user, adjusted manually
					'cancelled'),
				'cancelled' => array())),	// money weren't received for some reason, caused by invoice.cancelled
		'Owned'
	);
/**
 * List of belongsTo associations
 *
 * @var array
 * @access public
 */
	public $belongsTo = array(
		'Invoice' => array(
			'foreignKey' => 'parent_id'),
		'User' => array(
			'conditions' => array(
				'Refund.model' => 'User'),
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