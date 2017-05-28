<?php 
	class Home extends Controller{
		public function index($id = 1, $name = 'Fred'){
			$user = $this->model('User');
			$user->id = $id;
			$user->name = $name;
			$this->view('templates/head');
			$this->view('templates/navbar');
			$this->view('home/index', ['name'=>$user->name, 'id'=>$user->id]);
			$this->view('templates/footer');
		}
	}
?>
