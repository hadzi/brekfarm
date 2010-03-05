<?php
/**
 * Invoice model
 *
 * @property Producer $Producer
 * @property Refund $Refund
 */
class Invoice extends AppModel {
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
				'new' => array(				// new invoice waiting for payment from producer, caused by producer.new or producer.reinvoiced
					'sent',
					'cancelled'),
				'sent' => array(			// invoice was sent, caused by producer.invoiced
					'paid',
					'delayed',
					'cancelled'),
				'paid' => array(),			// money received, changes refund status to 'progress', caused by producer.paid
				'delayed' => array(			// invoice after term, caused by producer.delayed
					'paid',
					'cancelled'),
				'cancelled' => array())),	// cancelled invoice, changes refund status to 'cancelled', caused by producer.denied
		'Owned'
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
				'Invoice.model' => 'Producer'),
			'foreignKey' => 'foreign_key')
	);
/**
 * List of hasOne associations
 *
 * @var array
 * @access public
 */
	public $hasOne = array(
		'Refund' => array(
			'foreignKey' => 'parent_id',
			'dependent' => true)
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