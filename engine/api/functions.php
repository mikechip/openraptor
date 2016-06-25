<?php

/*
	** @comment Данный файл используется для объявления стандартных функций
	** @comment Они такие же обычные и серые для RAPTOR, как функции PHP для PHP...
	** @last_edit 22.08.2015 by 
	** @todo Убрать лишние функции
*/

function requestGet($param = false)
{
    if ($param == false) 
	{
        return $GLOBALS['GET'];
    } 
	else
	{
        return $GLOBALS['GET'][$param];
    }
}

function requestPost($param = false)
{
    if ($param == false) 
	{
        return $GLOBALS['POST'];
    } 
	else
	{
        return $GLOBALS['POST'][$param];
    }
}

function render($html)
{
    echo $html;
}

function raptor_print($hash)
{
    echo base64_decode($hash);
}

function MongoReserved($key)
{
    if (is_object($key) or $key == "_id" or $key == "mod" or is_array($key)) 
	{
        return true;
    } 
	else
	{
        return false;
    }
}

function toId($string)
{
    return Database::toId($string);
}

function raptor_date($input = false)
{
    if (empty($input) or $input == false) 
	{
        return date("d.m.Y G:i");
    } 
	else
	{
        return $input['day'] . "." . $input['month'] . "." . $input['year'] . " " . $input['hour'] . ":" . $input['minute'];
    }
}

function template($root)
{
	if(is_string(Cache::get(sha1($root)))) 
	{
		return Cache::get(sha1($root));
	}
    elseif (file_exists(TEMPLATE_ROOT . "/" . $root)) 
	{
		$handle = fopen(TEMPLATE_ROOT . "/" . $root, "r");
		$html = fread($handle, filesize(TEMPLATE_ROOT . "/" . $root));
		fclose($handle);
		return $html;
	}
	else 
	{
		return;
	}
}

function templater($root, $replaces)
{
	if(is_string(Cache::get(sha1($root)))) 
	{
		$html = Cache::get(sha1($root));
	}
	else 
	{
		if (filesize(TEMPLATE_ROOT . "/" . $root) <= 0) 
		{
			return "";
		}
		if (!file_exists(TEMPLATE_ROOT . "/" . $root)) 
		{
			return;
		}
		$handle = fopen(TEMPLATE_ROOT . "/" . $root, "r");
		$html = fread($handle, filesize(TEMPLATE_ROOT . "/" . $root));
		fclose($handle);
	}
    foreach ($replaces as $key => $value) 
	{
        $html = str_replace($key, $value, $html);
    }
    return $html;
}

function session($var)
{
    return $_SESSION[$var];
}

function __toString($object)
{
    if (is_object($object)) 
	{
        return $object->__toString();
    } 
	else
	{
        return $object;
    }
}

function array_search_unset($array, $value)
{
    $key = array_search($value, $array);
    if ($key !== false) 
	{
        unset($array[$key]);
    }
}

function log_error($file = 'error', $data = false)
{
	if($data === false) 
	{
		file_put_contents(LOGS_ROOT . SEPARATOR . "error.log", $data, FILE_APPEND);
		return 2;
	}
    file_put_contents(LOGS_ROOT . SEPARATOR . $file.".log", $data, FILE_APPEND);
	return 1;
}

function raptor_error($error, $trigger = true)
{
    if (!is_string($error)) 
	{
        return;
    }
    #Database::Insert("errors", array("text" => $error, "date" => raptor_date()));
    log_error("RAPTOR Error: $error \n");
    if ($trigger === true)
        trigger_error($error, E_USER_ERROR);
}

function raptor_warning($error, $trigger = true)
{
    if (!is_string($error)) 
	{
        return;
    }
    #Database::Insert("errors", array("text" => $error, "date" => raptor_date()));
    log_error("RAPTOR Warning: $error \n");
    if ($trigger === true)
        trigger_error($error, E_USER_WARNING);
}

function raptor_notice($error, $trigger = true)
{
    if (!is_string($error)) 
	{
        return;
    }
    #Database::Insert("errors", array("text" => $error, "date" => raptor_date()));
    log_error("RAPTOR Notice: $error \n");
    if ($trigger === true)
        trigger_error($error, E_USER_NOTICE);
}

function makeReport($name, $text, $date)
{
    $date = (!empty($date)) ? $date : raptor_date();
    Database::Insert("reports", array("author" => $name, "date" => $date, "message" => $text));
}

function checkConfig()
{
    return false; # Function deprecated
}

function check_syntax($code)
{
    return @eval('return true;' . $code);
}

function rpgjs_getcmd($id)
{
	if(is_string(Cache::get("rpgjs_cmd_" . $id)) and Cache::get("rpgjs_cmd_" . $id) != 0) 
	{
		$data = array($id => Cache::get("rpgjs_cmd_" . $id));
	}
	else 
	{
		$data = Database::GetOne("config", array("mod" => "locations"));
		Cache::set("rpgjs_cmd_" . $id, $data['_' . $id], 3600);
	}
    
    if ($data and isset($data['_' . $id])) 
	{
        return $data['_' . $id];
    } 
	else
	{
        return 0;
    }
}

function raptor_json_encode($string)
{
    return preg_replace("/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode($string));
}

function getScript($name)
{
	if(is_string(Cache::get("script_". $name))) 
	{
		return base64_decode(Cache::get("script_". $name));
	}
	else 
	{
		$code = Database::GetOne('scripts', array('name' => $name))['code'];
		Cache::set("script_". $name, $code, 86400);
		return base64_decode($code);
	}
}

function char($id = false)
{
	$id = __toString($id);
	
    if (isset($GLOBALS['chars'][$id]) and is_object($GLOBALS['chars'][$id])) 
	{
        return $GLOBALS['chars'][$id];
    } 
	else
	{
        $char = new Char();
		$GLOBALS['chars'][$id] = $char;
        return $char;
    }
}

function array_to_object($ar)
{
	return (object)$ar;
}

function CharById($id)
{
    return char($id);
}

function CharByName($name)
{
    $id = Database::GetOne("characters", array("name" => $name));
    if (is_array($id)) 
	{
        return char(__toString($id['_id']));
    } 
	else
	{
        return false;
    }
}

function checkTimers()
{
    $ts = Database::Get("timers", array());
    foreach ($ts as $array) 
	{
        if ($array['time'] <= time()) 
		{
            eval($array['code']);
            Database::Remove('timers', array("id" => $array['id']));
        }
    }
}

function createTimer($id, $time, $code)
{
    Database::Insert("timers", array("id" => $id, "time" => time() + $time, "code" => $code));
}

function createEventTimer($id, $time)
{
    createTimer($id, $time, "call_user_func('EventTimerExpired', " . $id . ");");
}

function check_player_events($id = false, $delete = false, $clearEval = true)
{
	if(!is_numeric($id)) { $id = $_SESSION['cid']; }
    $result = array("js" => array(), "eval" => array());
    $all = Cache::get('events_' . $id);
	if(!is_array($all)) 
	{ 
		return array(); 
	}
    foreach ($all as $a) 
	{
        if (isset($a['js'])) 
		{
            $result['js'][] = $a['js'];
        }
        if (isset($a['eval'])) 
		{
            $result['eval'][] = $a['eval'];
			if ($clearEval == true) 
			{
				$nm = array_search($a, $all);
				unset( $all[$nm] );
			}
        }
    }
    if ($delete == true) 
	{
        Cache::set('events_' . $id, '0', 0);
    } 
	elseif ($clearEval == true) 
	{
        Cache::set('events_' . $id, $all, 0);
    }
    return $result;
}

?>