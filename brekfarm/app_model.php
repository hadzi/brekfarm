<?php
/**
 * App-wide parent class for all models
 */
class AppModel extends Model {
/**
 * By default, use as lowest recursive as possible
 *
 * @var integer
 * @access public
 */
	public $recursive = -1;
/**
 * List of used behaviors
 *
 * @var array
 * @access public
 */
	public $actsAs = array('Containable');
}
?>