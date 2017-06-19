<?php
    class customer{
        private $_db, $_data, $_sessionName, $_cookieName, $_isLoggedIn, $_log;

        public function __construct($user = null){
            $this->_db = database::getInstance();
            $this->_sessionName = tray_config::get('session/session_name');
            $this->_cookieName = tray_config::get('remember/cookie_name');
            $this->_log = new log();

            if(!$user){
                if(session::is_around($this->_sessionName)) {
                    $user = session::get($this->_sessionName);

                    if($this->find($user)) {
                        $this->_isLoggedIn = true;
                    }else{
                        $this->logout();
                    }
                }
            }else {
                $this->find($user);
            }
        }

        public function update($fields = array(), $id = null){
            if(!$id && $this->isLoggedIn()){
                $id = $this->data()->id;
            }
            if(!$this->_db->update('users', $id, $fields)){
                throw new Exception('There was a problem updating data');
            }
        }

        public function create($fields = array()){
            if(!$this->_db->insert('users', $fields)){
                throw new Exception('<div class="alert alert-danger fade in" role="alert"><i class="fa fa-warning alert-danger"></i><strong>Oh snap!</strong> There was a problem creating an account.<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button></div>');	
            }
        }

        public function find($user = null){
            if($user){
                $field = (is_numeric($user)) ? 'id' : 'username';
                $data = $this->_db->get('users', array($field, '=', $user));

                if($data->count()){
                    $this->_data = $data->first();
                    return true;
                }
            }
        }

        public function login($username = null, $password = null, $remember = false){
            if(!$username && !$password && $this->exists()){
                session::put($this->_sessionName, $this->data()->id);
            }else{
                $user = $this->find($username);		
                if($user){
                    if($this->data()->password === hash::make($password, $this->data()->salt ) ){
                        session::put($this->_sessionName, $this->data()->id);
                        $this->_log->put('Login', 'User ID '.$this->data()->id.' Logged in');

                        if($remember) {
                            $encrypt = hash::unique();
                            $session = $this->_db->get('session', array('user_id', '=', $this->data()->id));

                            if(!$session->count()) {
                                $this->_db->insert('session', array( 'user_id' => $this->data()->id, 'encrypt' => $encrypt ));
                            } else {
                                $encrypt = $session->first()->encrypt;
                            }
                            cookie::put($this->_cookieName, $encrypt, config::get('remember/cookie_expiry'));
                        }
                        return true;
                    }
                }
            }	

            return false;
        }

        public function adminLogin($username = null, $password = null, $remember = false, $isAdmin = array('jadmin', 'admin') ){	
            if(!$username && !$password && $this->exists()){
                session::put($this->_sessionName, $this->data()->id);
            }else{
                $user = $this->find($username);		
                if($user){
					if( ($this->data()->password === hash::make($password, $this->data()->salt )) && ($this->hasPermission('admin') || $this->hasPermission('jadmin') )  ){
                         session::put($this->_sessionName, $this->data()->id);

                        if($remember){
                            $encrypt = hash::unique();
                            $session = $this->_db->get('session', array('user_id', '=', $this->data()->id));

                            if(!$session->count()){
                                $this->_db->insert('session', array('user_id' => $this->data()->id, 'encrypt' => $encrypt));
                            }else{
                                $encrypt = $session->first()->encrypt;
                            }

                            cookie::put($this->_cookieName, $encrypt, config::get('remember/cookie_expiry'));
                        }
                        return true;
                    }
                }
            }	
            return false;
        }

        public function hasPermission($key){
            $grp = $this->_db->get('groups', array('id', '=', $this->data()->groups ));

            if($grp->count()){
                $permissions = json_decode($grp->first()->permissions, true);
                if($permissions[$key] == true){
                    return true;
                }
            }
            return false;
        }

        public function exists(){
            return (!empty($this->_data)) ? true :false;
        }

        public function logout(){
            $this->_db->delete('session', array('user_id', '=', $this->data()->id));
            session::delete($this->_sessionName);
            cookie::delete($this->_cookieName);
        }

        public function data(){
            return $this->_data;
        }

        public function isLoggedIn(){
            return $this->_isLoggedIn;
        }
    }
?>