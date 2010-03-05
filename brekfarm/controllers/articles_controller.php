<?php
/**
 * Articles Controller
 *
 * @property Article $Article
 */
class ArticlesController extends AppController {
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
 * Shows list of articles in given category
 *
 * @param string $category
 * @return array
 * @access public
 * @todo
 */
	public function index($category = null) {
		$this->params['menu']['model'] = 'Article';
	}
}
?>