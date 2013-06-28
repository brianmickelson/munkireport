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


//===============================================================
// View
// For plain .php templates
//===============================================================
abstract class KISS_View {
	protected $file='';
	protected $vars=array();

	function __construct($file='',$vars='')  {
		if ($file)
			$this->file = $file;
		if (is_array($vars))
			$this->vars=$vars;
		return $this;
	}

	function __set($key,$var) {
		return $this->set($key,$var);
	}

	function set($key,$var) {
		$this->vars[$key]=$var;
		return $this;
	}

	//for adding to an array
	function add($key,$var) {
		$this->vars[$key][]=$var;
	}

	function fetch($vars='') {
		if (is_array($vars))
			$this->vars=array_merge($this->vars,$vars);
		extract($this->vars);
		ob_start();
		require($this->file);
		return ob_get_clean();
	}

	function dump($vars='') {
		if (is_array($vars))
			$this->vars=array_merge($this->vars,$vars);
		extract($this->vars);
		require($this->file);
	}

	static function expand_view_path($file)
	{
		if (strpos($file, "/") !== 0)
			return APP_PATH . 'views/' . $file;
		return $file;
	}

	static function do_fetch($file='',$vars='') {
		ob_start();
		if (is_array($vars))
			extract($vars);
		require(self::expand_view_path($file));
		return ob_get_clean();
	}

	static function do_dump($file='',$vars='') {
		if (is_array($vars))
			extract($vars);
		require(self::expand_view_path($file));
	}

	static function do_fetch_str($str,$vars='') {
		if (is_array($vars))
			extract($vars);
		ob_start();
		eval('?>'.$str);
		return ob_get_clean();
	}

	static function do_dump_str($str,$vars='') {
		if (is_array($vars))
			extract($vars);
		eval('?>'.$str);
	}
}