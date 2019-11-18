<?php
  session_start();

  //Flash Message helper
  //Example - flash('register_success', 'Thank you for registering with Us!', 'alert alert-danger');
  //Display in View - echo flash('register_success');
  function flash($name = '', $message = '', $class = 'alert alert-success'){
    //Text aligning to center
    $class = $class. ' text-center';
    if (!empty($name)){
      if (!empty($message) && empty($_SESSION[$name])){
        //Check session name exists
        if (!empty($_SESSION[$name])) {
          unset($_SESSION[$name]);
        }
        //Check session class exists
        if (!empty($_SESSION[$name. '_class'])) {
          unset($_SESSION[$name. '_class']);
        }
        $_SESSION[$name] = $message;
        $_SESSION[$name. '_class'] = $class;
      }else if(empty($message) && !empty($_SESSION[$name])) {
        $class = !empty($_SESSION[$name. '_class']) ? $_SESSION[$name. '_class'] : '';



        $flashAlert = '<div class="'.$class.' alert-dismissible fade show" role="alert">';
        $flashAlert .= ''.$_SESSION[$name].'';
        $flashAlert .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
        $flashAlert .= '<span aria-hidden="true">&times;</span>';
        $flashAlert .= '</button>';
        $flashAlert .=  '</div>';
        echo $flashAlert;
        unset($_SESSION[$name]);
        unset($_SESSION[$name. '_class']);
      }
    }
  }
