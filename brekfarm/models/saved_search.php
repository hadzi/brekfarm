<?php
/**
 * SavedSearch model
 *
 * @property Geocode $Geocode
 * @property User $User
 */
class SavedSearch extends AppModel {
/**
 * List of used behaviors
 *
 * @var array
 * @access public
 */
	public $actsAs = array(
		'Geocoded',
		'Sortable'
	);
/**
 * List of belongsTo associations
 *
 * @var array
 * @access public
 */
	public $belongsTo = array(
		'Geocode',
		'User'
	);
}
?>