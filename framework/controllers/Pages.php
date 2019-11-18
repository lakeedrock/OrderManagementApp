<?php
  /**
   *
   */
  class Pages extends Controller{

    public function __construct(){
      if (!(isset($_SESSION['user_id']) && isset($_SESSION['user_email']) && isset($_SESSION['user_name']))) {
        header('location: '. BASE_URL.'users/login');
      }
    }

    public function index(){
        $data = array(
            'title' => APP_NAME,
            'description' => 'Order Management Application developed on top of PHP MVC Framework by Lakshan Costa for AdCash.',
            'version' => APP_VERSION
        );
      $this->view('pages/index',$data);
    }
  }
