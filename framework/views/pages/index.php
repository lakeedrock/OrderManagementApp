<?php
  require APP_URL.'/views/inc/header.php';
?>

<div class="jumbotron jumbotron-fluid text-center mt-5">
  <div class="container ">
    <h2 class="display-4"><?php echo $data['title'] ?></h2>
    <p class="lead"><?php echo $data['description']?></p>
    <p class="lead">Version <span class="badge badge-success"><?php echo $data['version']; ?></span></p>
  </div>
</div>
<div class="container-fluid">
  <div class="col text-center">
    <a class="btn btn-lg btn-primary" href="<?php echo BASE_URL.'orders/index'; ?>" role="button">Goto Application</a>
  </div>
</div>
<?php
  require APP_URL.'/views/inc/footer.php';
?>
