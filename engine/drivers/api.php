<?php
/*
	Copyright (c) 2016 Boris Stelmah

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in all
	copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
	SOFTWARE.

	@last_edit 13.10.2015
	@comment External API driver (API main interance)
*/

class apiDriver 
{
	
	function __call($a1, $a2) 
	{
		Raptor::Header('Content-Type: application/json; charset=utf-8');
		
		if(!class_exists("ExtAPI"))
		{
			throw new Exception("Cannot find external API class. What happened?");
		}
		
		if(!isset($_GET['a']) and isset($GLOBALS['action']))
		{
			$_GET['a'] = $GLOBALS['action'];
		}
		
		if(strstr($_GET['a'], '.')) { $_GET['a'] = str_replace('.', '_', $_GET['a']); }
		
		if(isset($_GET['a']) and method_exists("ExtAPI", $_GET['a'])) 
		{
			echo ExtAPI::$_GET['a']($_GET);
		}
		else 
		{
			echo ExtAPI::_undefined_method($_GET);
		}
	}
	
}

