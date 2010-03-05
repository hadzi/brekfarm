<?php
/**
 * Favourite model
 *
 * @property Producer $Producer
 * @property User $User
 */
class Favourite extends AppModel {
/**
 * List of used behaviors
 *
 * @var array
 * @access public
 */
	public $actsAs = array(
		'Sortable'
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
				'Favourite.model' => 'Producer'),
			'foreignKey' => 'foreign_key'),
		'User'
	);
}
?>