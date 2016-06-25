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

	@last_edit 13.10.2015 by Mike
	@comment Admin interface driver
*/

class adminDriver {

    function __call($func, $args)
    {
		$func = strtolower(str_replace("action", "", $func));
		if(!file_exists(CACHE_ROOT . SEPARATOR . "installed.cache") and $func != 'install') 
		{ 
			Raptor::Redirect('/admin/install');
		}
        if (!char()->canUseAdmin($func)) 
		{
            die("403 Forbidden");
        }
		// additional checks; i wanna delete them in future
        if(!isset($_SESSION['cid']) and file_exists(CACHE_ROOT . SEPARATOR . "installed.cache")) 
		{ 
			die("403 Forbidden"); 
		}
		
        include_once(ADMIN_ROOT . SEPARATOR . "header.inc.php");
		
        if (strstr($func, 'ext_'))
		{
            include_once(MODS_ROOT . SEPARATOR . trim($func, 'ext_') . SEPARATOR . trim($func, 'ext_') . ".admin.php");
        } 
		else
		{
            include_once(ADMIN_ROOT . SEPARATOR . $func . ".php");
        }
		
        include_once(ADMIN_ROOT . SEPARATOR . "footer.inc.php");
    }

}
