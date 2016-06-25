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
	@comment Paid services. Sometimes works bad
	@todo Fix bugs
*/

class paidservicesDriver 
{

    function actionIndex() 
	{
		$params = Raptor::ModConfig('mod_paidservice');
        $main = new Templater;
        $main->import("boxes/ps_page.tpl");
        $main->setvar("%URL%", "http://" . $GLOBALS['url']);
        $main->setvar("%STORAGE_TPL_URL%", "/storage/tpl");
        $main->setvar("%YEAR%", date("Y"));
        $main->setvar("%CSS%", "<style>" . templater("css/game.css", array("%ROOT%" => "/storage/tpl")) . "</style>");
        $main->setvar("%GAME_TITLE%", $GLOBALS['name']);
        $main->setvar("%STORAGE_STATIC_URL%", "/storage/static");
		$result = '';
		if(isset($_GET['buy'])) 
		{
			if(!isset($params[$_GET['buy']]['time'])) { $main->setvar("%CONTENT%", "<h2>404 Not Found</h2>"); $main->renderEcho(); return 1; }
			if(char()->$params[$_GET['buy']]['currency'] < $params[$_GET['buy']]['cost']) { $main->setvar("%CONTENT%", "<h2>Недостаточно денег</h2>"); $main->renderEcho(); return 1; }
			char()->giveMoney(-$params[$_GET['buy']]['cost'], $params[$_GET['buy']]['currency']);
			eval($params[$_GET['buy']]['eval_bought']);
			createTimer($_GET['buy'], $params[$_GET['buy']]['time'], $params[$_GET['buy']]['eval_expired']);
		}
        foreach($params as $key => $value) 
		{
			if(!is_array($value)) { continue; }
			$result .= templater("boxes/ps_list.tpl", array("%ID%" => $key, "%NAME%" => $value['name'], "%COST%" => $value['cost'], "%TIME%" => $value['time'], "%CURRENCY%" => Database::GetOne("config", array("mod" => "currency"))[$value['currency']]['name'], "%TIME%" => $value['time']));
		}
		$main->setvar("%CONTENT%", $result);
        $main->renderEcho();
    }

}
