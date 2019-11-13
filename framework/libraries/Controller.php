<?php

  /**
   * Base controller
   * Loads the Models and Views
   */
  class Controller {
    // Load Model
    public function model($model){
      // Require model file
      require_once '../framework/models/'.$model.'.php';
      // Instanciate model
      return new $model();
    }

    // Load view
    public function view($views, $data = []){
      // Check for view file
      if (file_exists('../framework/views/'.$views.'.php')) {
        require_once '../framework/views/'.$views.'.php';
      }else{
        // view does not exists
        die('View does not exists');
      }

    }

    public function __construct(){
      // code...
    }
  }

?>
