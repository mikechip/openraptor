<?php
	
	/*
		@last_edit 09.10.2015 by Mike
		@comment Класс для работы с новостями
	*/
	
	class News
	{
		public static function get($id = null, $limit = 10)
		{
			if(is_null($id))
			{
				return Database::Get("news", array('public' => '1'))->limit($limit);
			}
			else
			{
				return Database::GetOne("news", array('_id' => toId($id)));
			}
		}
		
		public static function edit($id, $news)
		{
			return Database::Edit("news", array("_id" => toId($id)), $news);
		}
		
		public static function create($id = null, $title = '-', $short = '', $full = '', $date = null)
		{
			if($id === null) { $id = new MongoId(); }
			if($date === null) { $date = raptor_date(); }
			
			return Database::Insert("news", array("_id"=>$id,"short"=>$short,"title"=>$title,"full"=>$full,"date"=>raptor_date(),"public"=>'1'));
		}
	}
	