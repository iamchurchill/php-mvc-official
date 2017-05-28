<?php 
    class Alert{
        private static $_instance = NULL;
        private function __construct(){
        }
        public static function getInstance(){
            if(!isset(self::$_instance)){
                self::$_instance = new Alert();
            }
            return self::$_instance;
        }
        public function mail($to, $subject, $message){
			$headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/plain; charset=iso-8859-1';
            $headers[] = 'From: BlueCollar<info@traytontech.com>';
            $headers[] = 'X-Priority: 1';
            $headers[] = 'X-MSMail-Priority: High';
            $headers[] = 'Importance: High';
            $headers[] = 'X-Mailer: PHP/' . phpversion();
            if(mail($to, $subject, $message, implode("\r\n", $headers))){
                return true;
            }
			return false;
		}
    }
?>
