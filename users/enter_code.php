<?php
require '../config.php';
require  ROOT_PATH . '../db/db.php';
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>WatchNow - Enter Code</title>
    <link rel="stylesheet" href="../css/enter_code.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
  </head>
  <body>

    <section class="main">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#dc4ce3" fill-opacity="1" d="M0,192L18.5,213.3C36.9,235,74,277,111,256C147.7,235,185,149,222,133.3C258.5,117,295,171,332,197.3C369.2,224,406,224,443,192C480,160,517,96,554,101.3C590.8,107,628,181,665,197.3C701.5,213,738,171,775,176C812.3,181,849,235,886,229.3C923.1,224,960,160,997,133.3C1033.8,107,1071,117,1108,106.7C1144.6,96,1182,64,1218,69.3C1255.4,75,1292,117,1329,165.3C1366.2,213,1403,267,1422,293.3L1440,320L1440,0L1421.5,0C1403.1,0,1366,0,1329,0C1292.3,0,1255,0,1218,0C1181.5,0,1145,0,1108,0C1070.8,0,1034,0,997,0C960,0,923,0,886,0C849.2,0,812,0,775,0C738.5,0,702,0,665,0C627.7,0,591,0,554,0C516.9,0,480,0,443,0C406.2,0,369,0,332,0C295.4,0,258,0,222,0C184.6,0,148,0,111,0C73.8,0,37,0,18,0L0,0Z"></path></svg>

        <div class="navigation">
            <div class="brand">
                <h1>WatchNow - Enter Code</h1>
            </div>
            <div class="toggle">
                <i class="fas fa-bars"></i>
            </div>
            <div class="nav-menu">
                <div class="close">
                    <i class="fas fa-window-close"></i>
                </div>
                <ul>
                    <li><a href="../">Home</a></li>
                    <li><a href="#">Community</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
        </div>


        <div class="container">
            <div class="photo">
                <img src="../img/register.svg">
            </div>

            <div class="form-cont">
                <div class="inner-form">
                    <img src="../img/log.svg" alt="avatar" srcset="">

                    <div class="social-login">
                        <h3 style="color:white;">Final setep to get your password </h3>
                        <h4 style="color:white; padding-top:25px;padding-bottom:25px;">Enter the code which we sent to your email address here</h4>
                    </div>

                    <div class="input-area">
                        <form action="enter_code.php" method="post">
                           <?php if (count($errors) > 0): ?>

                              <?php foreach ($errors as $error): ?>
                                <script>alert("<?php echo $error; ?>");</script>
                              <?php endforeach;?>

                            <?php endif;?>
                            <div>
                                <i class="fas fa-lock"></i>
                                <input type="text" name="code" onfocus="this.value=''" placeholder="Enter code" required>
                            </div>
                            <button class="btn" type="submit" name="done-btn">Done</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

  </body>
</html>
