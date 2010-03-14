<?php
/**
 * Jquery Helper
 *
 * If this helper is used from beforeRender() of another helper, JqueryHelper::beforeRender()
 * callback should be called as first one.
 * If this helper is used from afterRender() of another helper, JqueryHelper::afterRender()
 * callback should be called as last one.
 *
 *
 * @Property FormHelper $Form
 * @Property JavascriptHelper $Javascript
 */
class JqueryHelper extends AppHelper {
/**
 * List of used helpers
 *
 * @var array
 * @access public
 */
	public $helpers = array('Html', 'Form', 'Javascript');
/**
 * Callback switcher
 *
 * @var boolean
 * @access public
 */
	public $enabled = true;

/**
 * Map library names to filenames
 *
 * @var array
 * @access public
 */
	public $fileMap = array(
		'jquery' => array(
			'http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',
			'jquery.min'),
		'ui' => array(
			'http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/jquery-ui.min.js',
			'jquery-ui.min'),
		'tools' => array(
			'http://cdn.jquerytools.org/1.1.2/tiny/jquery.tools.min.js',
			'jquery.tools.min'),
		'form' => 'jquery.form.min',
		'potato.menu' => 'jquery.ui.potato.menu.min',
		'date' => 'date.js',
		'jquery.datePicker' => 'jquery.datePicker.js',
		'cake.datePicker' => 'cake.datePicker.js'
	);
/**
 * Default options for used jquery plugins
 *
 * @var array $options
 * @access public
 */
	public $options = array(
		'form' => array(
			'target' => null,
			'beforeSubmit' => null,
			'success' => null,
			'dataType' => null,
			'resetForm' => false,
			'clearForm' => false),
		'tabs' => array(
			'effect' => 'default'),
		'datePicker' => array(
			'format' => '%Y-%m-%d')
	);
/**
 * Flag if scripts should be cached in (and used as) file JS.'filename.js'
 *
 * @var boolean $cacheToFile
 * @access public
 */
	public $cacheToFile = false;
/**
 * Script filenames to use
 *
 * @var array $_uses
 * @access protected
 */
	protected $_uses = array();
/**
 * Scripts to execute
 *
 * @var array $_scripts
 * @access protected
 */
	protected $_scripts = array();
/**
 * beforeRender callback
 *
 * @access public
 */
	public function beforeRender() {
		if (!$this->enabled) {
			return;
		}
		parent::beforeRender();
		$this->Javascript->cacheEvents(false, true);
		$this->Javascript->enabled = false;
	}
/**
 * Callback
 *
 * @access public
 */
	public function afterRender() {
		if (!$this->enabled) {
			return;
		}
		parent::afterRender();
		$isDebug = (bool) Configure::read();
		if (empty($this->params['isAjax']) && empty($this->params['requested'])) {
			if (!empty($this->_uses)) {
				$uses = array();
				foreach ($this->_uses as $file) {
					if (isset($this->fileMap[$file])) {
						if (is_array($this->fileMap[$file])) {
							$uses[] = $isDebug ? $this->fileMap[$file][1] : $this->fileMap[$file][0];
						} else {
							$uses[] = $this->fileMap[$file];
						}
					} else {
						$uses[] = $file;
					}
				}
				$this->Javascript->link($uses, false);
			}
			$this->Javascript->cacheEvents($this->cacheToFile, true);
			$this->Javascript->writeEvents(false);
		} else {
			echo $this->Javascript->writeEvents();
		}
		if (!empty($this->_scripts)) {
			$scripts = "\$(function(){\n\t";
			$scripts .= join(ife($isDebug, "\n\t", ' '), $this->_scripts);
			$scripts .= "\n});";
			$View = ClassRegistry::getObject('view');
			$View->set('bottom_for_layout', $this->Javascript->codeBlock($scripts));
		}
	}
/**
 * Include script(s) to execute in head of layout
 *
 * @param mixed
 * @access public
 */
	public function script() {
		if ($args = func_get_args()) {
			$this->_scripts = array_merge($this->_scripts, $args);
		}
	}
/**
 * Include link(s) to javascript file(s) in head of layout
 *
 * @param mixed
 * @access public
 */
	public function uses() {
		if ($args = func_get_args()) {
			$this->_uses = array_merge($this->_uses, $args);
		}
	}
/**
 * jQuery date picker
 *
 * @param string $fieldName
 * @param array $options
 * @return string
 * @access public
 */
	public function datePicker($fieldName, $options = array()) {
		$this->uses('jquery', 'date', 'jquery.datePicker', 'cake.datePicker');
		$this->Html->css('datePicker', null, array('inline' => false));

		$this->Form->setEntity($fieldName);
		$htmlAttributes = $this->Form->domId($options);
		$divOptions['class'] = 'date';
		$options['type'] = 'date';
		$options['div']['class'] = 'date';
		$options['dateFormat'] = 'DMY';
		$options['minYear'] = isset($options['minYear']) ? $options['minYear'] : (date('Y') - 20);
		$options['maxYear'] = isset($options['maxYear']) ? $options['maxYear'] : (date('Y') + 20);

		$options['after'] = $this->Html->image('calendar.png', array('id'=> $htmlAttributes['id'], 'style'=>'cursor:pointer'));

		if (isset($options['empty'])) {
			$options['after'] .= $this->Html->image('b_drop.png', array('id'=> $htmlAttributes['id']."_drop", 'style'=>'cursor:pointer'));
		}
		$output = $this->Form->input($fieldName, $options);
		$output .= $this->Javascript->codeBlock("datepick('" . $htmlAttributes['id'] . "','01/01/" . $options['minYear'] . "','31/12/" . $options['maxYear'] . "');");
		return $output;
	}
/**
 * jQuery ajax replacement for FormHelper::create()
 *
 * @param string $model
 * @param array $options
 * @return string
 * @access public
 */
	public function form($model = null, $options = array()) {
		$this->uses('jquery', 'form');
		$_options = $this->_parseOptions($options, 'form');
		if (!isset($options['id'])) {
			$options['id'] = 'form' . intval(rand());
		}
		$stringKeys = array('beforeSubmit', 'success');
		if ($_options['target'] && ('$' == substr($_options['target'], 0, 1))) {
			$stringKeys[] = 'target';
		}
		$script = '$("#' . $options['id'] . '").ajaxForm(';
		$script .= $this->Javascript->object($_options, false, '', '', $stringKeys, false);
		$script .= ');';
		$this->script($script);
		return $this->Form->create($model, $options);
	}
/**
 * Accordion from jQuery tools
 *
 * @param array $options
 * @return void
 * @access public
 */
	public function tabs($options = array()) {
		$switcher = empty($options['switcher']) ? 'ul.switcher' : $options['switcher'];
		$panes = empty($options['panes']) ? 'div.panes > div' : $options['panes'];
		$this->uses('jquery', 'tools');
		if ($options) {
			$_options = $this->_parseOptions($options, 'tabs');
			if ($_options['effect'] == 'ajax') {
				$this->script('$.tools.tabs.addEffect("ajax",function(tabIndex,done){'
					. 'var pane = this.getPanes().hide().eq(tabIndex);'
					. 'if (pane.is(":empty")){'
						. 'pane.load(this.getTabs().eq(tabIndex).attr("href"));'
					. '}'
					. 'pane.show();'
					. 'done.call();'
				. '});');
			}
			$stringKeys = array('effect');
			$append = ', ' . $this->Javascript->object($_options, false, '', '', $stringKeys, false);
		} else $append = '';
		$this->script(sprintf('$("%s").tabs("%s"%s);', $switcher, $panes, $append));
	}
/**
 * Parse options from $this->options, remove them from passed argument
 *
 * @param array $options
 * @param string $name
 * @return array
 * @access protected
 */
	protected function _parseOptions(&$options, $name) {
		$result = array();
		if (isset($this->options[$name])) {
			$result = $this->options[$name];
			foreach ($this->options[$name] as $key => $value) {
				if (isset($options[$key])) {
					$result[$key] = $options[$key];
					unset($options[$key]);
				}
			}
		}
		return $result;
	}
}
?>