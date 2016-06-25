<?php

/*
	@last_edit 22.08.2015
	@last_autor Mike
	@comment Главный пользовательский интерфейс в игре
	@todo Гибкость
*/

class pDriver 
{

    function actionIndex()
    {
        if (!isset($_SESSION['id'])) 
		{
            header("Location: /");
            die();
        }
        if (!isset($_SESSION['cid'])) 
		{
            header("Location: /cabinet");
            die();
        }

        $main = new Templater;
        $main->import("interface/game.tpl");
        $main->setvar("%URL%", "http://" . @$GLOBALS['url']);
        $main->setvar("%STORAGE_TPL_URL%", "/storage/tpl");
        $main->setvar("%YEAR%", date("Y"));
        $main->setvar("%CSS%", "<style>" . templater("css/game.css", array("%ROOT%" => "/storage/tpl")) . "</style>");
        $main->setvar("%GAME_TITLE%", $GLOBALS['name']);
        $main->setvar("%STORAGE_STATIC_URL%", "/storage/static");
		
		$GLOBALS['current_loc_info'] = Raptor::ModConfig('locations')[char()->map];
		if(!isset($GLOBALS['current_loc_info']['type']) or $GLOBALS['current_loc_info']['type'] == 'default') 
		{
			$main->setvar("%GUI%", Raptor::get_string('undefined_gui'));
		}
		else 
		{
			$GLOBALS['current_loc_type_info'] = Raptor::ModConfig('location_types')[$GLOBALS['current_loc_info']['type']];
			require_once(MODS_ROOT . SEPARATOR . $GLOBALS['current_loc_type_info']['module'] . SEPARATOR . "location_type.php");
			$main->setvar("%GUI%", isset($GLOBALS['to_gui']) ? $GLOBALS['to_gui'] : Raptor::get_string('undefined_gui') );
		}
		
        $main->setvar("%CHATBOX%", template("boxes/chat.tpl"));
        $main->renderEcho();
    }

}
