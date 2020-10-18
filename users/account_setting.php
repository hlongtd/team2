<?php
require '../db/db.php';
//session_start();
// redirect user to login page if they're not logged in
if (!isset($_SESSION['id'])) {
    header('location: ../users/login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WatchNow </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/account_setting.css">

    <script src="https://kit.fontawesome.com/a076d05399.js" defer></script>
</head>

<body>

    <nav class="navbar navbar-expand-md navbar-light bg-light mb-3 linearColor">
        <div class="container">
            <a class="navbar-brand" href="../videos/index.php"><img src="../img/logo.png" alt=""></a>
            <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                    <li class="nav-item text-center mr-auto">
                        <div class="nav-link dropdown" >
                            <a data-toggle="dropdown" href="#"><img src="../img/user.png" style="height: 24px;" alt="User avatar"></a>
                            <a data-toggle="dropdown"><?php echo $_SESSION['user_name'];?>
                              <span><img src="../img/carret.png" style="height:20px;"></span>
                            </a>
                            <ul class="dropdown-menu text-center">
                              <li><a href="#">Profile</a></li>
                              <div class="dropdown-divider"></div>
                              <li><a class="dropdown-header">Setting</a></li>
                              <div class="dropdown-divider"></div>
                              <li><a href="../users/account_setting.php">Account Setting</a></li>
                              <li><a href="#">Privacy</a></li>
                              <li><a href="#">FAQs</a></li>
                              <li><a href="#">Terms & Conditions</a></li>
                              <div class="dropdown-divider"></div>
                              <li><a href="../users/logout.php">Logout</a></li>
                            </ul>
                        </div>
                    <!--end .nav-item-->
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row setting-panel">
            <div class="col-md-3">
              <div class="left-bar">
                <div class="nav">
                  <a class="nav-item nav-link" id="nav-acc-tab" data-toggle="tab" href="#nav-acc"
                  role="tab" aria-controls="nav-acc" aria-selected="true">
                  <span><img style="height:20px;" src="../img/cog.png"><span>Account Setting</a>
                  <a href="#">Change Password</a>
                  <a href="#">Privacy</a>
                  <a href="#">Contact Us</a>
                </div>
              </div>
            </div>


            <div class="col-md-9">
              <div class="right-bar">
                <h3>Account Setting</h3>
                <h3>Name:</h3>
                <h3>Email:</h3>
                <h3>Birth day:</h3>
                <h3>Phone:</h3>
                <h3>Address:</h3>

              </div>
            </div>
        <!--end .row-->
        </div>
    </div>







    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>

</body>

</html>
