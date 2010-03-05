<?php
/**
 * Products Controller
 *
 * @property Product $Product
 */
class ProductsController extends AppController {
/**
 * beforeFitler callback
 *
 * @return void
 * @access public
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index');
	}
/**
 * Shows list of products in given category
 *
 * @param string $category
 * @return array
 * @access public
 * @todo
 */
	public function index($category = null) {
	}
}
?>