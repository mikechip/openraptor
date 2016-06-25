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
*/

define("WEBSITE", 1); # Anti-hacking
define("CLIENT_USE", 1);

define("SITE_ROOT", __DIR__); # Root of website

/**
 * Дополнение от 0paquity
 */
define("MODE", "production");
/**
 * Всего режимов работы два
 * dev - режим разработки
 * production - работающий прототип или конечный продукт
 */

if(MODE == 'cloud')
{
	error_reporting(0);
	define("HIDE_ERRORS", true);
	$GLOBALS['debug'] = false;
	require_once(SITE_ROOT . "/engine/cloudmgr.php");
}
elseif(file_exists(SITE_ROOT . "/engine/cache/installed.cache") or file_exists(SITE_ROOT . "/engine/config.php")) 
{ 
	if(MODE !== 'dev')
	{
		error_reporting(0);
		define("HIDE_ERRORS", true);
		$GLOBALS['debug'] = false;
	}
	require_once(SITE_ROOT . "/engine/config.php");
}
else 
{
	$GLOBALS['database'] = "__temp";
	$GLOBALS['debug'] = false;
	$GLOBALS['url'] = $_SERVER['SERVER_NAME'];
	error_reporting(0);
	define("HIDE_ERRORS", true);
}
require_once(SITE_ROOT . "/engine/api.php");
require_once(SITE_ROOT . "/engine/router.php");

Router::Start();

