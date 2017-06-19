<?php
    class validator{
        private $_passed = false,
                $_errors = array(),
                $_db = null;

        public function __construct(){
            $this->_db = database::getInstance();
        }

        public function validate($source, $items = array()){
            foreach($items as $item => $rules){
                foreach ($rules as $rule => $rule_value) {
                    $value = trim($source[$item]);
                    $item = escape($item);

                    if($rule ==='required' && empty($value)){
                        $this->addError("<div class='error'>{$item} is required.</div>");
                    }else{
                        switch ($rule) {
                            case 'min':
                                if(strlen($value) < $rule_value){
                                    $this->addError("<div class='error'>{$item} must be a minimum of {$rule_value} chars.</div>");
                                }
                                break;
                            case 'max':
                                if(strlen($value) > $rule_value){
                                    $this->addError("<div class='error'>{$item} must be a maximum of {$rule_value} chars.</div>");
                                }
                                break;
                            case 'matches':
                                if($value != $source[$rule_value]){
                                    $this->addError("<div class='error'>{$rule_value} must match {$item}.</div>");
                                }
                                break;
                            case 'unique':
                                 $check = $this->_db->get($rule_value, array($item, '=', $value));
                                if($check->count()){
                                    $this->addError("<div class='error'>{$item} already exists.</div>");
                                }	
                                break;
                            case 'uniqueEmail':
                                $check = $this->_db->get($rule_value, array($item, '=', $value));
                                if($check->count()){
                                    $this->addError("<div class='error'>{$item} already exists.</div>");
                                }	
                                break;		
                            default:
                                # code...
                                break;
                        }

                    }
                }
            }

            if(empty($this->_errors)){
                $this->_passed = true;
            }
            return $this;
        }

        private function addError($error){
            $this->_errors[] = $error;
        }

        public function errors(){
            return $this->_errors;
        }	

        public function passed(){
            return $this->_passed;
        }	
    }
?>