<?php
require '../config.php';
require ROOT_PATH . '../db/db.php';

// if(isset($_SESSION["id"])) {
//   header("location: ../index.php"); /*change directory*/
// }
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <script src="../js/fontawesome/login.js" defer></script>
  <!-- <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous" defer></script> -->

  <!-- <link rel="stylesheet" type="text/css" href="../css/style.css"> -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <link rel="stylesheet" href="../css/login.css" />
  <title>WatchNow - Sign in & Sign up</title>

</head>

<body>
  <div class="container">
    <div class="forms-container">
      <div class="signin-signup">
        <form action="login.php" class="sign-in-form" method="post">
          <h2 class="title">Sign in</h2>
          <!--Display error-->
          <?php if (count($errors_login) > 0): ?>
            <div class="alert alert-danger">
              <?php foreach ($errors_login as $error): ?>
              <li>
                <?php echo $error; ?>
              </li>
              <?php endforeach;?>
            </div>
          <?php endif;?>
          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" name="user_name" id="user_name" value="<?php if(isset($_COOKIE["user_name"])) { echo $_COOKIE["user_name"]; } ?>" placeholder="Username" required/>
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" id="password" value="<?php if(isset($_COOKIE["password"])) { echo $_COOKIE["password"]; } ?>" placeholder="Password" required/>
          </div>
          <div><input type="checkbox" name="remember" id="remember" <?php if(isset($_COOKIE["user_name"])) { ?> checked <?php } ?> />
                <label for="remember-me">Remember me</label>
          </div>
          <input type="submit" name="login-btn" id="login-btn" value="Login" class="btn solid" />
          <!-- Button trigger modal -->
          <p>Forgot password? <a href="" data-toggle="modal" data-target="#confirm-reset-password">Reset it</a></p>
        </form>

        <form action="login.php" name="sign-up-form" class="sign-up-form" method="post">
          <h2 class="title">Sign up</h2>
          <!--Display error-->
          <?php if (count($errors) > 0): ?>
            <div class="alert alert-danger">
              <?php foreach ($errors as $error): ?>
              <li>
                <?php echo $error; ?>
              </li>
              <?php endforeach;?>
            </div>
          <?php endif;?>

          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" name="username" onfocus="this.value=''" placeholder="Username" required />
          </div>
          <div class="input-field">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" placeholder="Email" required />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" id="password-signup" onfocus="this.value=''" placeholder="Password" required />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" name="passwordConf" id="confirmPassword-signup" onfocus="this.value=''" placeholder="Confirm Password" required />
          </div>
          <div class="input-field">
            <i class='fas fa-venus-mars'></i>
            <input type="gender" name="gender" onfocus="this.value=''" placeholder="Male or Female" required />
          </div>
          <input type="checkbox" onclick="showPassword()">Show Password
          <input type="submit" name="signup-btn" class="btn" value="Sign up" />
        </form>

      <!--end .signin-signup-->
      </div>
    <!--end .forms-container-->
    </div>

    <div class="panels-container">
      <div class="panel left-panel">
        <div class="content">
          <h3>Don't have an account yet?</h3>
          <p>
            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Debitis,
            ex ratione. Aliquid!
          </p>
          <button class="btn transparent" id="sign-up-btn">
            Sign up
          </button>
        </div>
        <img src="../img/log.svg" class="image" alt="" />
      </div>
      <div class="panel right-panel">
        <div class="content">
          <h3>One of us ?</h3>
          <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum
            laboriosam ad deleniti.
          </p>
          <button class="btn transparent" id="sign-in-btn">
            Sign in
          </button>
        </div>
        <img src="../img/register.svg" class="image" alt="" />
      </div>
    <!--end .panels-container-->
    </div>
  <!--end .container-->
  </div>

  <!-- Modal : confirm reset password-->
  <div class="modal fade" id="confirm-reset-password" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Confirm reset password</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>We will reset your password and send a new random password to your email.
            So to make sure that email is yourself, first we will send a confirmation code to your email address.
            You need to fill it correctly on the next page. Then, we will send a new password to your email.
            Finally, you should change your password when you login to protect your account.</p>
          <p>Have good luck with your next journeys.</p>
          <p>Your best friend,</p>
          <p>WatchNow.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn" id="btn-next-confirm" data-toggle="modal" data-target="#enter-email">Next</button>
        <!--end .modal-footer-->
        </div>
      </div>
    </div>
  <!--end .modal confirm reset password-->
  </div>


  <!-- Modal: enter email address -->
  <div class="modal fade" id="enter-email" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabe2" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabe2">First, fill in your email address which you registered your account</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form name="confirm-email-form" class="confirm-email-form" action="login.php" method="post">
            <!--Display error-->
            <?php if (count($errors_confirm_email) > 0): ?>
                <?php foreach ($errors_confirm_email as $error): ?>
                  <script>alert("<?php echo $error; ?>")</script>
                <?php endforeach;?>
            <?php endif;?>

            <div class="input-field">
              <i class="fas fa-envelope"></i>
              <input type="email" name="confirm-email" onfocus="this.value=''" placeholder="Email" required />
              <span class="throw_error"></span>
              <span id="success"></span>
            </div>
            <button type="submit" id="confirm-email-btn" name="confirm-email-btn" class="btn" data-toggle="modal"  data-target="#<?php echo $enter_code;?>">Next</button>
          </form>
        <!--end .modal-body-->
        </div>
      <!--end .modal-content-->
      </div>
    <!--end .modal-dialog-->
    </div>
  <!--end .modal enter email address-->
  </div>







  <script type="text/javascript" defer>
    const sign_in_btn = document.querySelector("#sign-in-btn");
    const sign_up_btn = document.querySelector("#sign-up-btn");
    const container = document.querySelector(".container");

    <?php if (count($errors) > 0): ?>
      container.classList.add("sign-up-mode");
    <?php endif;?>

    sign_up_btn.addEventListener("click", () => {
      container.classList.add("sign-up-mode");
    });
    sign_in_btn.addEventListener("click", () => {
      container.classList.remove("sign-up-mode");
    });

    function showPassword() {
      var password = document.getElementById("password-signup");
      var confirmPassword = document.getElementById("confirmPassword-signup");
      if (password.type === "password" && confirmPassword.type === "password") {
        password.type = "text";
        confirmPassword.type = "text";
      } else {
        password.type = "password";
        confirmPassword.type = "password";
      }
    }
  </script>






  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

  <!--Hide mode confirm reset password-->
  <script>
  $(document).ready(function(){
    $("#btn-next-confirm").click(function() {
      $("#confirm-reset-password").hide();
    });
  });
  </script>


</body>

</html>
