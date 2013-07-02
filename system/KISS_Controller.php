<?php
/*****************************************************************
Copyright (c) 2008-2009 {kissmvc.php version 0.7}
Eric Koh <erickoh75@gmail.com> http://kissmvc.com

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without
restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following
conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.
*****************************************************************/








/**
 * If error_reporting() returns 0, exceptions are caught and then silenced.
 * Otherwise, exceptions are formatted and redirected to the 'error'
 * controller's _500() function and view.
 */
function kissmvc_exception_handler($exception)
{
	//if (error_reporting() === 0)
	//	return;
	$controller = KISS_Controller::get_instance();
	$controller->controller = 'error';
	$controller->action = '500';
	$controller->set('exception', $exception);
	$controller->render_view();
}


function kissmvc_error_handler($errno, $errstr, $errfile, $errline, $errcontext)
{
	//throw new RuntimeException($errstr, $errno);
}
set_exception_handler('kissmvc_exception_handler');
set_error_handler('kissmvc_error_handler', E_ALL);





//===============================================================
// Controller
// Parses the HTTP request and routes to the appropriate function
//===============================================================
class KISS_Controller
{
	public    $controller;
	public    $action;
	public    $params = array();
	public    $view_format = '';
	public    $default_route;
	protected $controller_path = '../app/controllers/'; //with trailing slash
	protected $web_folder = '/'; //with trailing slash
	protected $request_uri_parts = array();
	protected $view_data = array();
	protected $view_scripts = array();
	protected $view_styles = array();
	protected $view_title = '';
	protected $prevent_render = FALSE;
	protected static $_instance;




	/**
	 * Creates a new singleton instance of the KISS_Controller class.
	 * The framework will generally call this on its own, and subsequent calls
	 * with throw exceptions. Use KISS_Controller::getInstance() instead.
	 */
	public function __construct($controller_path,
								$web_folder,
								$default_controller,
								$default_action)
	{

		if (self::$_instance != NULL)
			throw new Exception('KISS_Controller is a singleton. Please use KISS_Controller::get_instance() to access the controller instance.');

		self::$_instance = $this;
		$this->default_route = $default_controller . "/" . $default_action;
		$this->controller_path=$controller_path;
		$this->web_folder=$web_folder;
		$this->controller=$default_controller;
		$this->action=$default_action;
		$this->explode_http_request();
		$this->parse_http_request();
		$this->before_route();
		$this->route_request();
	}




	/**
	 * Override this method to execute code before the request is routed to a
	 * controller. This is an ideal place to hook in authentication
	 * mechanisims.
	 */
	public function before_route()
	{

	}




	/**
	 * Returns the singleton instance of the KISS_Controller.
	 */
	public static function get_instance()
	{
		return self::$_instance;
	}




	/**
	 * Allows the caller to prevent this controller instance from automatically
	 * locating and displaying a view.
	 *
	 * While the default behavior is to allow automatic rendering, calling this
	 * method with no parameters will disable the behavior. It can be turned 
	 * back on by passing FALSE as the only parameter.
	 *
	 * @param Boolean $aBool Set to TRUE to disable rendering, FALSE (the 
	 * default) to enabled it.
	 */
	public function prevent_render($aBool = TRUE)
	{
		$this->prevent_render = (bool)$aBool;
	}




	/**
	 * Allows the caller to specify data that should be passed along to the
	 * automatically generated view instance. Note: calling
	 * prevent_render(TRUE) will render this method useless.
	 *
	 * @param String $key The variable name used by the view.
	 * @param mixed $val The value stored in the variable named $key.
	 */
	public function set($key, $val)
	{
		if ($this->prevent_render !== FALSE)
			return;
		$this->view_data[$key] = $val;
	}




	/**
	 * Allows the caller to access the data identified by $key that will be
	 * passed along to the view (assuming $prevent_render isn't TRUE).
	 *
	 * @return mixed The requested data, or boolean FALSE if the key hasn't 
	 * been set.
	 */
	public function get($key)
	{
		if (isset($this->view_data[$key]))
			return $this->view_data[$key];
		return FALSE;
	}




	/**
	 * Allows the caller to add a stylesheet to the header of the main layout.
	 *
	 * @param String $sheet Relative (to /css/) path to the desired stylesheet.
	 */
	public function add_stylesheet($sheet)
	{
		if ( ! in_array($sheet, $this->view_styles))
			$this->view_styles[] = $sheet;
	}




	/**
	 * Allows the caller to add a javascript file to the header of the main 
	 * layout.
	 *
	 * @param String $script Relative (to /js/) path to the desired script.
	 */
	public function add_script($script)
	{
		if ( ! in_array($script, $this->view_scripts))
			$this->view_scripts[] = $script;
	}




	/**
	 * Allows the caller to set the title of the page as rendered by the main 
	 * layout template.
	 *
	 * @param String $title The value that should appear in the <title></title>
	 * tag.
	 */
	public function set_view_title($title)
	{
		$this->view_title = htmlentities($title);
	}




	/**
	 * Provides the caller with the current title that will be set in the main
	 * layout's <title></title> tags.
	 * 
	 * @return String
	 */
	public function get_view_title()
	{
		return (string)$this->view_title;
	}




	/**
	 * Generates an instance of the KISS_View class with the appropriate view
	 * file and data arguments. If $prevent_render is FALSE (the default), 
	 * this method is called automatically after the action function has
	 * finished executing.
	 *
	 * The data passed to the view can be set with the KISS_Controller:set
	 * method. The view file that will be loaded is in the form of
	 * views/<controller>/[<view_format>/]<action>.php
	 */
	public function render_view()
	{
		if ($this->prevent_render !== FALSE)
			return;

		$view_format_component = '';
		if ($this->view_format != NULL)
			$view_format_component = $this->view_format . '/';

		$view_path = APP_PATH . 'views/' . $this->controller . '/'
			. $view_format_component . $this->action . '.php';

		if ( ! file_exists($view_path))
			$this->request_not_found('Unable to locate view for the current request. Tried ' . $view_path);

		$view = new View($view_path, $this->view_data);
		$content = $view->fetch();
		$layout = new View(APP_PATH . 'views/layouts/'
			. $view_format_component . 'mainlayout.php',
			array(
				"layout_content" => $view->fetch(),
				"layout_styles" => $this->view_styles,
				"layout_scripts" => $this->view_scripts,
				"layout_page_title" => $this->view_title
			)
		);
		$layout->dump();
	}




	/**
	 * Allows the caller to send an HTTP redirect back to the browser.
	 * Calling this method will cause the application to terminate to avoid
	 * any views from rendering.
	 *
	 * @param String $uri The relative URI to which the user should be 
	 * redirected.
	 */
	public function redirect($uri)
	{
		header('Location: ' . $this->web_folder . $uri);
		exit;
	}




	private function explode_http_request()
	{
		$requri = $_SERVER['REQUEST_URI'];
		if (strpos($requri, $this->web_folder) === 0)
			$requri = substr($requri, strlen($this->web_folder));
		if (strpos($requri, "index.php") === 0)
			$requri = ltrim(str_replace("index.php", '', $requri), "/");
		while(strpos($requri, "//") !== FALSE)
			$requri = str_replace("//", "/", $requri);
		$this->request_uri_parts = $requri ? explode('/', $requri) : array();
	}




	/**
	 * This function parses the HTTP request to get the controller name, action
	 * name and parameter array.
	 */
	private function parse_http_request()
	{
		$this->params = array();
		$p = $this->request_uri_parts;
		if (isset($p[0]) && $p[0])
			$this->controller = $p[0];
		
		if (isset($p[1]) && $p[1])
			$this->action = $p[1];
		
		if (isset($p[2]))
		{
			$this->params = array_slice($p,2);
			$this->_decode_params_array();
		}
		
		$this->_discover_view_format();
	}




	protected function _decode_params_array()
	{
		foreach($this->params as &$param)
		{
			$param = rawurldecode($param);
		}
	}




	/**
	 * Determines if the last param, action, or controller (in that order) has 
	 * a file extension and stores it in $this->view_format after cleaning the
	 * appropriate value. This format is used to convert, say, '.json' into a 
	 * view path of 'views/<controller>/json/<action>.php'
	 */
	private function _discover_view_format()
	{
		$valid_formats = array('json', 'xml', 'csv', 'pdf', 'plist');
		if (count($this->params) > 0)
		{
			$last_idx = count($this->params) -1;
			$parts = $this->_get_basename_and_ext($this->params[$last_idx]);
			if ( ! in_array($parts['ext'], $valid_formats))
				return;
			$this->params[$last_idx] = $parts['basename'];
			$this->view_format = $parts['ext'];
		}
		else
		{
			$action_parts = $this->_get_basename_and_ext($this->action);
			$controller_parts = $this->_get_basename_and_ext($this->controller);
			if ($action_parts['ext'] !== '')
			{
				$this->action = $action_parts['basename'];
				$this->view_format = $action_parts['ext'];
			}
			else if ($controller_parts['ext'] !== '')
			{
				$this->controller = $controller_parts['basename'];
				$this->view_format = $controller_parts['ext'];
			}
		}
		if (in_array($this->view_format, $valid_formats) == FALSE)
			$this->view_format = NULL;
	}




	/**
	 * Used by KISS_Controller::_discover_view_format().
	 */
	private function _get_basename_and_ext($string)
	{
		$ext = pathinfo($string, PATHINFO_EXTENSION);
		$basename = basename($string, "." . $ext);
		return array("basename" => $basename, "ext" => $ext);
	}




	/**
	 * This method maps the controller name and action name to the file 
	 * location of the .php file to include using PHP's reflection classes.
	 */
	private function route_request() {
		$controller_file = $this->controller_path . $this->controller
			. '/' . $this->action . '.php';

		if ( ! file_exists($controller_file))
			$this->request_not_found(
				'Controller file not found: ' . $controller_file);

		$function = '_'.$this->action;

		if (function_exists($function))
			$this->request_not_found('Invalid function name: ' . $function);

		@require($controller_file);

		if ( ! function_exists($function))
			$this->request_not_found('Function not found: ' . $function);

		call_user_func_array($function, $this->params);
		$this->render_view();
	}




	/**
	 * Called by the framework whenever a route or route's resource cannot be 
	 * found.
	 *
	 * @param String $msg The message to display to the end user.
	 */
	public function request_not_found($msg = '')
	{
		if ($this->controller != 'error' && $this->action != '404')
		{
			$this->controller = 'error';
			$this->action = '404';
			$this->set_view_title("Error 404");
			$this->set("error_message", $msg);
			$this->prevent_render = FALSE;
			$this->render_view();
			exit;
		}
		else
		{
			echo "<h1>Error 404 - Resource Not Found</h1>"
				. "<p>" . $msg . "</p>"
				. "<p>Additionally, KISSMVC was unable to properly locate resources to display this page.</p>";
			exit;
		}
	}




	/**
	 * Converts the relative path provided into a full URL.
	 *
	 * @param String $uri The URI to convert.
	 * @param Boolean $include_hostname Defaults to FALSE; TRUE will include 
	 * the hostname in the result.
	 * @return String
	 */
	public function full_uri($uri = '', $include_hostname = FALSE)
	{
		$result = $include_hostname === TRUE ? WEB_DOMAIN : '';
		$result .= WEB_FOLDER . $uri;
		return $result;
	}
}