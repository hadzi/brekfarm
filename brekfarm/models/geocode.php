<?php
/**
 * Geocode model
 *
 * @property Producer $Producer
 * @property SavedSearch $SavedSearch
 * @property User $User
 */
class Geocode extends AppModel {
/**
 * List of used behaviors
 *
 * @var array
 * @access public
 */
	public $actsAs = array(
		'Owned'
	);
/**
 * List of hasMany associations
 *
 * @var array
 * @access public
 */
	public $hasMany = array(
		'Producer' => array(
			'dependent' => false),
		'SavedSearch' => array(
			'dependent' => true),
		'User' => array(
			'dependent' => false)
	);
}
?>