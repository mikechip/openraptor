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

	@last_edit 22.08.2015 by Mike
	@comment Страницы с игроками
	@todo Качественный, или хотя бы нормальный код
*/

class playerDriver 
{

    function __call($func, $args)
    {
        $func = strstr($func, 'action') ? str_replace("action", "", $func) : 'index';
		
        if ($func != 'index') 
		{
            $array = Char::find(array("name" => $func));
        } 
		else
		{
            $array = Char::find(array("_id" => toId($_SESSION['cid'])));
            $func = $array['name'];
        }
        if (!isset($array['name'])) 
		{
            die("<h1>Персонаж " . $func . " не найден</h1>");
        }
        $params = Raptor::ModConfig('params');
        $main = new Templater;
        $main->import("interface/playerinfo.tpl");
        $main->setvar("%URL%", "http://" . $GLOBALS['url']);
        $main->setvar("%STORAGE_TPL_URL%", "/storage/tpl");
        $main->setvar("%YEAR%", date("Y"));
        $main->setvar("%CSS%", "<style>" . templater("css/game.css", array("%ROOT%" => "/storage/tpl")) . "</style>");
        $main->setvar("%GAME_TITLE%", $GLOBALS['name']);
        $main->setvar("%STORAGE_STATIC_URL%", "/storage/static");
        $main->setvar("%GUI%", template("interface/GUI.tpl"));
        $main->setvar("%CHATBOX%", template("boxes/chat.tpl"));
        $params_all = '';

        foreach ($array as $key => $value) 
		{
            if (MongoReserved($key) or MongoReserved($value) or strstr($key, "p_")) 
			{
                continue;
            }
            $main->setvar("%" . $key . "%", $array[$key]);
        }

        foreach ($params as $key => $value) 
		{
            if (!strstr($key, "p_")) 
			{
                continue;
            }
            $v = char(__toString($array['_id']))->getParam($key);
            $main->setvar("%" . $key . "%", $v);
            $params_all .= '<p><b>' . $value['name'] . '</b>: ' . $v . '</p>';
        }
        $main->setvar("%PARAMS_ALL%", $params_all);
        $main->renderEcho();
    }

}
