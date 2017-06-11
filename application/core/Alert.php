<?php 
    class Alert{
        private static $_instance = NULL;
        private function __construct(){
        }
        public static function getInstance(){
            if(!isset(self::$_instance)){
                self::$_instance = new alert();
            }
            return self::$_instance;
        }
        public function mail($to, $subject, $message, $html = false){
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = ($html == true) ? 'Content-type: text/html; charset=UTF-8' : 'Content-type: text/plain; charset=iso-8859-1';
            $headers[] = 'From:233COMIC <info@traytontech.com>';
            $headers[] = 'X-Priority: 1';
            $headers[] = 'X-MSMail-Priority: High';
            $headers[] = 'Importance: High';
            $headers[] = 'X-Mailer: PHP/' . phpversion();
			return mail($to, $subject, $message, implode("\r\n", $headers));
		}
    }
?>