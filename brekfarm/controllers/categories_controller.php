<?php
/**
 * Categories Controller
 *
 * @property Category $Category
 */
class CategoriesController extends AppController {
/**
 * List of used helpers
 *
 * @var array
 * @access public
 */
	public $helpers = array('Form', 'Tree', 'Jquery');
/**
 * Returns trees of categories
 *
 * @return array
 * @access public
 * @todo restrict result to non-empty categories only
 */
	public function menu() {
		$this->admin_index();
		return $this->viewVars['categories'];
	}
/**
 * Categories adding
 *
 * @param string $model
 * @return void
 * @access public
 */
	public function admin_add($model = null) {
		if (!$this->data) {
			if (!$model || !isset($this->Category->hasMany[$model])) {
				$this->Session->setFlash(__('Invalid Category', true));
				$this->redirect(array('action' => 'index'));
			}
			$this->data = $this->Category->create(array('model' => $model));
		} else {
			$model = $this->data['Category']['model'];
			$this->Category->setScope($model);
			if ($this->Category->save($this->data, true, array('title', 'model'))) {
				$this->Session->setFlash(__('A new category has been created.', true));
				$this->redirect(array('action' => 'index', '#' => $model));
			}
		}
		$this->set('categories', $this->Category->generatetreelist(array('model' => $model), null, null, ' - '));
	}
/**
 * Administration list of categories
 *
 * @return void
 * @access public
 */
	public function admin_index() {
		$categories = array();
		foreach (array_keys($this->Category->hasMany) as $model) {
			$this->Category->setScope($model);
			$categories[$model] = $this->Category->find('tree');
		}
		$this->set('categories', $categories);
	}
}
?>