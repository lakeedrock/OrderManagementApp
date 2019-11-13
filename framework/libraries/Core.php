<?php

/**
 * Framework Core class
 *Creates URL & loads core Controller
 *URL FORMAT - /controler/method/params
 *
 */

 class Core{
   protected $__currentController = 'Pages';
   protected $__currentMethod = 'index';
   protected $__params = [];

   public function __construct()
   {
     $url = $this->getURL();

     //Look in controller for the first array_count_values
     if (file_exists('../framework/controllers/'.ucwords($url[0]).'.php')) {
       // If exists, set as current controller
       $this->__currentController = ucwords($url[0]);
       //unset 0 index
       unset($url[0]);
     }
     //Require the current controller class/var_dump('../framework/controllers/'.$this->__currentController);
     require_once '../framework/controllers/'.$this->__currentController.'.php';
     //Instantiate controller class
     $this->__currentController = new $this->__currentController;

     //Check for the method in second array_count_value
     if (isset($url[1])) {
       // Check whether method exist in the controller
       if (method_exists($this->__currentController,$url[1])) {
         $this->__currentMethod = $url[1];
         //unset index 1
         unset($url[1]);
       }
     }

     //Get URL params
     $this->__params = $url ? array_values($url) : [];
     //Call a callback with array of params
     call_user_func_array([$this->__currentController,$this->__currentMethod],$this->__params);
     //Unset index 2
     unset($url[2]);

   }

   public function getURL()
   {
     if (isset($_GET['url'])) {
       $url = rtrim($_GET['url'],'/');
       $url = filter_var($url,FILTER_SANITIZE_URL);
       $url = explode('/',$url);
       return $url;
     }
   }
 }



