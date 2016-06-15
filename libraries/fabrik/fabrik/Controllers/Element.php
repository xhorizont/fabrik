<?php
/**
 * Fabrik Element Controller
 *
 * @package     Joomla
 * @subpackage  Fabrik
 * @copyright   Copyright (C) 2005-2015 fabrikar.com - All rights reserved.
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

namespace Fabrik\Controllers;

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Fabrik Element Controller
 *
 * @package     Joomla
 * @subpackage  Fabrik
 * @since       1.5
 */
class Element extends Controller
{
	/**
	 * Is the view rendered from the J content plugin
	 *
	 * @var  bool
	 */
	public $isMambot = false;

	/**
	 * Should the element be rendered as readonly
	 *
	 * @var  string
	 */
	public $mode = false;

	/**
	 * Id used from content plugin when caching turned on to ensure correct element rendered
	 *
	 * @var  int
	 */
	public $cacheId = 0;

	/**
	 * Display the view
	 *
	 * @return  null
	 */
	public function display()
	{
		$input = $this->input;
		$viewName = $input->get('view', 'element', 'cmd');
		$viewType = $this->doc->getType();

		// Set the default view name from the Request
		$view = &$this->getView($viewName, $viewType);

		// $$$ rob 04/06/2011 don't assign a model to the element as its only a plugin

		$view->editable = ($this->mode == 'readonly') ? false : true;

		// Display the view
		$view->error = $this->getError();

		return $view->display();
	}

	/**
	 * Save an individual element value to the fabrik db
	 * used in inline edit table plugin
	 *
	 * @return  null
	 */
	public function save()
	{
		$input = $this->input;
		$listModel = $this->getModel('list', 'FabrikFEModel');
		$listModel->setId($input->getInt('listid'));
		$rowId = $input->get('rowid');
		$key = $input->get('element');
		$key = array_pop(explode('___', $key));
		$value = $input->get('value');
		$listModel->storeCell($rowId, $key, $value);
		$this->mode = 'readonly';
		$this->display();
	}
}