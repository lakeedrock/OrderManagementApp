<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
  <div class="container">
    <a class="navbar-brand" href="<?php echo BASE_URL ?>"><?php echo APP_NAME?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav ml-auto">
        <?php if(isset($_SESSION['user_id'])) : ?>
          <li class="nav-item">
            <h4 class="text-primary">Welcome <span class="badge badge-success"><?php echo $_SESSION['user_name']; ?></span></h4>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="<?php echo BASE_URL ?>users/logout">Logout</a>
          </li>
        </ul>
        <?php else : ?>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL ?>users/register">Register</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL ?>users/login">Login</a>
            </li>
          <?php endif; ?>
        </ul>
    </div>
  </div>
</nav>
