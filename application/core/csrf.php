<?php
    class csrf{
		
        public static function generate(){
            return session::put(config::get('session/token_name'), md5(uniqid()) );
        }

		public static function csrf_token_tag(){
			return '<input type="hidden" name="csrf_token" value="' . self::generate() . '" >';
		}
		
		public static function csrf_token_delete(){
			$tokenName = config::get('session/token_name');
			return session::delete($tokenName);
		}
		
        public static function csrf_token_is_valid($token){
            $tokenName = config::get('session/token_name');

            if(session::is_around($tokenName) && $token === session::get($tokenName)){
               /* session::delete($tokenName);*/
                return true;
            }
            return false;
        }
		
    }
?>