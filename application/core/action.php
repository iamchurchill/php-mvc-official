<?php
	class action{
		public static function allowed_get_params($allowed_params=[]){
			$allowed_array=[];
			foreach($allowed_params as $param){
				if(isset($_GET[$param])){
					$allowed_array[$param] = self::clean($_GET[$param]);
				}else{
					$allowed_array[$param] = NULL;
				}
			}
			return $allowed_array;
		}
		
		public static function has_format_matching($value, $regex='//'){
			return preg_match($regex, $value);
		}
		
		public static function clean($value){
			return htmlentities(trim($value), ENT_QUOTES, 'UTF-8');
		}
	}
?>