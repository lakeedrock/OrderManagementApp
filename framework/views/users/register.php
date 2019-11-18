<?php
  require APP_URL.'/views/inc/header.php';
?>

<div class="row">
  <div class="col-md-6 mx-auto">
    <div class="card card-body bg-light mt-5">
      <img src="<?php echo BASE_URL; ?>images/adcash.png" alt="adcash logo" class="w-50 mx-auto">
      <h4 class="lead text-center">Create An Account</h4>
      <?php flash('register_fail'); ?>
      <form class="" action="<?php echo BASE_URL; ?>users/register" method="post">
        <!-- Name -->
        <div class="form-group">
          <label for="name">Name: <sup>*</sup></label>
          <input type="text" name="name" class="form-control form-control-lg <?php echo (!empty($data['name_error'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>">
          <span class="invalid-feedback"><?php echo $data['name_error']; ?></span>
        </div>
        <!-- Email -->
        <div class="form-group">
          <label for="name">Email: <sup>*</sup></label>
          <input type="email" name="email" class="form-control form-control-lg <?php echo (!empty($data['email_error'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
          <span class="invalid-feedback"><?php echo $data['email_error']; ?></span>
        </div>
        <!-- Password -->
        <div class="form-group">
          <label for="password">Password: <sup>*</sup></label>
          <input type="password" name="password" class="form-control form-control-lg <?php echo (!empty($data['password_error'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
          <span class="invalid-feedback"><?php echo $data['password_error']; ?></span>
        </div>
        <!-- Confirm Password -->
        <div class="form-group">
          <label for="confirm_password">Confirm Password: <sup>*</sup></label>
          <input type="password" name="confirm_password" class="form-control form-control-lg <?php echo (!empty($data['confirm_password_error'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_password']; ?>">
          <span class="invalid-feedback"><?php echo $data['confirm_password_error']; ?></span>
        </div>

        <div class="row">
          <div class="col">
            <input type="submit" name="" value="Register" class="btn btn-success btn-block">
            <a href="<?php echo BASE_URL; ?>users/login" class="btn btn-primary btn-block">Have an Account? Login</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php
  require APP_URL.'/views/inc/footer.php';
?>
