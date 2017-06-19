<?php
    class upload{
        private $_file, $_extention, $_destination, $_feedback_message, $_path, $_file_size;
     
        public function __construct($_file, $_extention, $_destination, $_file_size=2097152){
            $this->_file = $_file;
            $this->_extention = $_extention;
            $this->_destination = $_destination;
            $this->_file_size = $_file_size;
        }
        
        public function sendMultipleFilesToServer() {
            if(file_exists($this->_destination)){
                foreach($_FILES[$this->_file]['name'] as $f => $name) {  
                    if ($_FILES[$this->_file]['error'][$f] == 4) {
                        continue; // Skip file if any error found
                    }
                    if ($_FILES[$this->_file]['error'][$f] == 0) {	  
                        
                        if ($_FILES[$this->_file]['size'][$f] > 2097152) {
                            $message[] = "$name is too large!.";
                            continue; // Skip large files
                        }elseif(!in_array(pathinfo($name, PATHINFO_EXTENSION), $this->_extention) ){
                            $message[] = "$name is not a valid format";
                            continue; // Skip invalid file formats
                        }else{ // No error found! Move uploaded files 
                            if(move_uploaded_file($_FILES[$this->_file]["tmp_name"][$f], $this->_destination . $name)) {
                                $count++; // Number of successfully uploaded files
                            }
                        }
                    }
                }
                $this->setFeedback('FILES UPLOADED SUCCESSFULLY');
                             
            }else{
                $this->setFeedback('UPLOAD DIRECTORY DOESN\'T EXIST');
            }  
        }
        
        public function sendFileToServer(){
            if(file_exists($this->_destination)){
                
                $file_extention = explode('.', $_FILES[$this->_file]['name']);  $file_extention = strtolower(end($file_extention));
                
                if(in_array($file_extention, $this->_extention) === true){
                    if(!$_FILES[$this->_file]['size'] <= $this->_file_size){
                        if(empty($this->_feedback_message)==true){
                            
                           if(file_exists($this->_destination . $_FILES[$this->_file]['name'])){                             
                                $new_file_name = md5(substr($_FILES[$this->_file]['name'], 0, strripos($_FILES[$this->_file]['name'], '.'))) . date('Y') . substr($_FILES[$this->_file]['name'], strripos($_FILES[$this->_file]['name'], '.'));
                                move_uploaded_file($_FILES[$this->_file]['tmp_name'], $this->_destination . $new_file_name);       
                                $this->setFeedback('UPLOADED SUCCESSFULLY');
                                $this->setPath($this->_destination . $new_file_name);
                           }else{
                                move_uploaded_file($_FILES[$this->_file]['tmp_name'], $this->_destination . $_FILES[$this->_file]['name']);
                                $this->setFeedback('UPLOADED SUCCESSFULLY');
                                $this->setPath($this->_destination . $_FILES[$this->_file]['name']);
                           }
                                                                               
                        }else{
                            $this->getFeedback();
                        }
                    }else{
                        $this->setFeedback('FILE SIZE CAN\'T BE MORE THAN 2MB');
                    }
                }else{
                    $this->setFeedback('EXTENTION NOT ALLOWED');
                }        
            }else{
                $this->setFeedback('UPLOAD DIRECTORY DOESN\'T EXIST');
            }                                 
        }
        
        private function setFeedback($feedback_message){
            $this->_feedback_message = $feedback_message;
        }
        
        public function getFeedback(){
            return $this->_feedback_message;
        }
        
        private function setPath($path){
            $this->_path = $path;
        }
        
        public function getPath(){
            return $this->_path;
        }
    }
?>