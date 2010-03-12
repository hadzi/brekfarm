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
		$this->_permissions['add'] = '_isAllowedProducer';
	}
/**
 * Adding products by producers
 *
 * @return void
 * @access public
 * @todo
 */
	public function add() {
		if (!empty($this->data)) {
			$this->Product->create();
			if ($this->Product->save($this->data)) {
				$this->Session->setFlash(__('The Product has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Product could not be saved. Please, try again.', true));
			}
		}
		$categories = $this->Product->Category->find('list');
		$producers = $this->Product->Producer->find('list');
		$this->set(compact('categories', 'producers'));
	}
/**
 * Shows list of products in given category
 *
 * @param string $category
 * @return void
 * @access public
 * @todo
 */
	public function index($category = null) {
	}
/**
 * Authorization check
 *
 * @return boolean
 * @access protected
 * @todo add check of max amount of related products
 */
	protected function _isAllowedProducer() {
		return (bool) $this->Auth->user('Producer');
	}
}
?>