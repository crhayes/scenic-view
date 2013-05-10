<?php

/**
 * Scenic View
 *
 * https://github.com/crhayes/scenic-view
 *
 * Scenic View is a tiny library that provides view and template inheritance.
 * The syntax is straight PHP but is very clean and expressive.
 *
 * @package		Scenic View
 * @author 		Chris hayes <chris@chrishayes.ca>
 * @copyright	(c) 2013 Chris Hayes <http://chrishayes.ca>
 * @license		MIT License
 */

namespace Scenic;

class CrappyViewException extends \Exception {}

class View
{
	/**
	 * Store the path to the views folder.
	 * 
	 * @var string
	 */
	private $viewPath;

	/**
	 * The view being extended.
	 * 
	 * @var string
	 */
	private $extendedView;

	/**
	 * Store the contents of sections.
	 * 
	 * @var array
	 */
	private $sections;

	/**
	 * The currently opened (started) section.
	 * 
	 * @var string
	 */
	private $openSection;

	/**
	 * Store the path to the view.
	 *
	 * @param string 	$viewPath
	 */
	public function __construct($viewPath)
	{
		$this->viewPath = realpath($viewPath);
	}

	/**
	 * Render the given view.
	 * 
	 * @param  string 	$viewName
	 * @param  array 	$data
	 * @return string
	 */
	public function render($viewName, $data)
	{
		$this->data = $data;

		$view = $this->load($viewName);

		$view = ($this->extendedView) ? $this->load($this->extendedView) : $view;

		return $view;
	}

	/**
	 * Load the given view and return the contents.
	 *
	 * @param  string 	$viewName
	 * @return string
	 */
	public function load($viewName)
	{
		$viewPath = $this->viewPath.DS.$viewName;

		if ( ! file_exists($viewPath)) {
			throw new CrappyViewException("This view is not scenic... view does not exist: $viewPath");
		}

		$scenic = $this;

		$extends = function($view) use ($scenic) {
			$scenic->extend($view);
		};

		$section = function($name) use ($scenic) {
			$scenic->section($name);
		};

		$end = function() use ($scenic) {
			echo $scenic->stop();
		};

		$show = function($view) use ($scenic) {
			echo $scenic->show($view);
		};

		$include = function($view) use ($scenic) {
			echo $scenic->partial($view);
		};

		ob_start();

		extract($this->data);
		require $viewPath;

		return ob_get_clean();
	}

	/**
	 * Extend a parent View.
	 *
	 * @param  string 	$viewName
	 * @return void
	 */
	public function extend($viewName)
	{
		$this->extendedView = $viewName;
	}

	/**
	 * Include a partial view.
	 * 
	 * @param  string 	$viewName
	 * @return void
	 */
	public function partial($viewName)
	{
		return $this->load($viewName);
	}

	/**
	 * Start a new section.
	 * 
	 * @param  string 	$name
	 * @return void
	 */
	public function section($name)
	{
		$this->openSection = $name;
		ob_start();
	}

	/**
	 * Close a section and return the buffered contents.
	 *
	 * @return string
	 */
	public function stop()
	{
		$name = $this->openSection;

		$buffer = ob_get_clean();

		if ( ! isset($this->sections[$name])) {
			$this->sections[$name] = $buffer;
		} elseif (isset($this->sections[$name])) {
			$this->sections[$name] = str_replace('@parent', $buffer, $this->sections[$name]);
		}

		return $this->sections[$name];
	}

	/**
	 * Show the contents of a section.
	 *
	 * @param  string 	$name
	 * @return string
	 */
	public function show($name)
	{
		if(isset($this->sections[$name]))
		{
			return $this->sections[$name];
		}
	}
}