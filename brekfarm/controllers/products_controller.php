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
		if (!$this->data) {
			$data = array();
			if ($this->Auth->user('role') !== 'admin') {
				$producer = $this->Auth->user('Producer');
				$data['producer_id'] = $producer['id'];
			}
			$this->data = $this->Product->create($data);
		} else {
			$this->Product->create();
			$fields = array('description', 'price', 'unit', 'category_id', 'producer_id');
			if ($this->Product->save($this->data, true, $fields)) {
				$this->Session->setFlash(__('The Product has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Product could not be saved. Please, try again.', true));
			}
		}
		$categories = $this->Product->Category->generatetreelist(array('model' => 'Product'), null, null, ' - ');
		$producers = ($this->Auth->user('role') === 'admin') ? $this->Product->Producer->find('list') : null;
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