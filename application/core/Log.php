<?php
	class Log{
		public function log_action($action, $msg=""){
			$new_file = file_exists('../logs.txt') ? true : false ;
			if ($handle = fopen('../logs.txt', 'a')) {
				$time_of_action = strftime('%Y-%m-%d %H:%M:%S', time());
				$content = "{$time_of_action} | {$action}: {$msg}\n";
				fwrite($handle, $content);
				fclose($handle);
			}else{
				echo "Could not access file for writing!";
			}
		}
	}
?>