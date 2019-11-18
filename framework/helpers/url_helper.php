<?php
  //Simple Page Redirect
  //add $controller,$method,$params likewise. Time being $url
  function redirect($url)
  {
    header('location: '. BASE_URL.$url);
  }
