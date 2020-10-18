<?php
session_start();

require '../config.php';
require '../videos/autoload.php';
require "../videos/src/Helpers.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require  ROOT_PATH . '/users/vendor/phpmailer/phpmailer/src/Exception.php';
require ROOT_PATH . '/users/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require ROOT_PATH . '/users/vendor/phpmailer/phpmailer/src/SMTP.php';

//library for check email which exists in gmail
require_once(SMTP_PATH . '/smtpvalidateclass.php');

\Cloudinary::config(array(
   "cloud_name" => "dbjatetvr",
   "api_key" => "991915825348962",
   "api_secret" => "wsNszI3XnFE3PAlNtdEr6c_nAjw"
));

$url = "https://res.cloudinary.com/dbjatetvr/video/upload/";

$username = "";
$email = $gender = "";
$errors = $errors_login= $errors_confirm_email = [];
$enter_code = "";


$conn = new mysqli('localhost', 'root', '', 'watchnow');

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// SIGN UP USER
if(isset($_POST['signup-btn'])) {

  $token = bin2hex(random_bytes(50)); // generate unique token

  //Check username
  $username = $_POST['username'];
  //check white space of input
  if(strpos($username, ' ') > 0) {
    $errors['username'] = "Username should not have white space.";
  }

  $sql = "SELECT * FROM users WHERE user_name='$username' LIMIT 1";
  $result = $conn->query($sql);

  if(!$result) {
    exit($conn->error);
  } else if ($result->num_rows > 0) {
    $errors['username'] = "Username already existed.";
  }

  //Check email
  //exists in project's db
  $email = $_POST['email'];
  $sql = "SELECT * FROM `users` WHERE `email`='$email' LIMIT 1";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
      $errors['email'] = "Email already existed.";
  }
  //check email valid?
  $sanitizedEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
  if(!$email == $sanitizedEmail && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Invalid email";
  }

  //Check email exist in google db
  $emails = array($email);
  $sender = 'thanhtai762000@gmail.com';
  $SMTP_Valid = new SMTP_validateEmail();
  $result = $SMTP_Valid->validate($emails, $sender);
  if($result[$emails[0]]) {
    //echo "valid";
  } else {
    $errors['email'] = "Your email address does not exist!! Please go to google.com and create an account.";
  }

  //Check password
  $password = $_POST['password'];
  $confirm_password = $_POST['passwordConf'];
  if(strlen($password) < 8) {
    $errors['password'] = 'Password should be at least 8 characters in length.';
  }

  //password match
  if ($password !== $confirm_password) {
   $errors['password'] = "Password does not match!";
  }
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT); //encrypt password

  //gender
  $gender = trim($_POST['gender']);
  if( strtolower($gender) != 'male' && strtolower($gender) != 'female') {
    $errors['gender'] = "Gender is wrong format!";
  } else {
    $gender = trim($_POST['gender']);
  }


  if (count($errors) === 0) {
    $query = "INSERT INTO `users` SET `user_name`=?, email=?, password=?, gender=?, token=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssss', $username, $email, $password, $gender, $token);
    $result = $stmt->execute();

    if ($result) {
        $user_id = $stmt->insert_id;
        $stmt->close();

        // send verification email to user
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->Mailer = "smtp";

        $mail->SMTPDebug  = 1;
        $mail->SMTPAuth   = TRUE;
        $mail->SMTPSecure = "tls";
        $mail->Port       = 587;
        $mail->Host       = "smtp.gmail.com";
        $mail->Username   = "thanhtai762000@gmail.com";
        $mail->Password   = "thanhtai76";

        $mail->IsHTML(true);
        $mail->AddAddress($email , $username);
        $mail->SetFrom("thanhtai762000@gmail.com", "WatchNow" /*"from-name"*/);
        //$mail->AddReplyTo("reply-to-email@domain", "reply-to-name");
        //$mail->AddCC("cc-recipient-email@domain", "cc-recipient-name");
        $mail->Subject = "Verification link for Verify Your Email Address";
        $content = "
          <h1>Welcome to our page. One more step to start your journey.</h1>
          <p>To activation your account, click the link below: </p>
          <p><a href=\"http://localhost/watchnow/users/verify_email.php?token=$token\">Verify Email!</a></p>
          <p>Sincerely,</p>
          <p>WatchNow</p>
        ";

        $mail->MsgHTML($content);
        if(!$mail->Send()) {
          echo "Error while sending Email.";
          var_dump($mail);
        }

        echo '<script>alert("Please Check Your Email for Verification Code")</script>';

        $_SESSION['id'] = $user_id;
        $_SESSION['user_name'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['verified'] = false;
        $_SESSION['message'] = 'Register successfully.';
        $_SESSION['type'] = 'alert-success';


        header('location: ../users/login.php'); /*change directory*/
    } else {
      $_SESSION['error_msg'] = "Database error: Could not register user";
    }
  }
}



//LOGIN
if(isset($_POST['login-btn'])) {
    $username = $_POST['user_name'];
    $password = $_POST['password'];

    $query = "SELECT * FROM `users` WHERE `user_name`=? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);

    if($stmt->execute()) {
      $result = $stmt->get_result();
      if($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if($user['verified'] == 0) {
          $errors_login['login_fail'] = "Account not verify.Check your email!";
          //echo "<script>alert('Your account does not verify!')</script>";

        } else if(password_verify($password, $user['password'])) { // password match
          $stmt->close();
          //Remeber user feature
          if(!empty($_POST["remember"])) {
            setcookie ("user_name",$_POST["user_name"],time()+ (10 * 365 * 24 * 60 * 60));
            setcookie ("password",$_POST["password"],time()+ (10 * 365 * 24 * 60 * 60));
          } else {
            if(isset($_COOKIE["user_name"])) {
              setcookie ("user_name","");
            }
            if(isset($_COOKIE["password"])) {
              setcookie ("password","");
            }
          }

          $_SESSION['id'] = $user['id'];
          $_SESSION['user_name'] = $user['user_name'];
          $_SESSION['email'] = $user['email'];
          $_SESSION['verified'] = $user['verified'];
          $_SESSION['message'] = "You are logged in!";
          $_SESSION['type'] = "alert-success";
          header('location: ../videos/index.php'); /*change dirctory*/
          exit(0);
        } else { // don't match
          $errors_login['login_fail'] = "Wrong username or password!";
        }
      } else {
        $errors_login['login_fail'] = "User does not exist!";
      }
    }
}




//Confirm reset password: enter email
if(isset($_POST['confirm-email-btn'])) {

    $confirm_email_btn = $_POST['confirm-email-btn'];
    //Need sanitize confirm-email?
    //
    //Catch case verified = 0: not verified => can't reset
    //
    $email = $_POST['confirm-email'];
    $query = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if($result->num_rows > 0) {
          $user = $result->fetch_assoc();

          //var_dump($user);
          $confirm_code = generateConfirmCode();
          $sql = "UPDATE `users` SET `confirm_code` = '$confirm_code' WHERE `id` = {$user['id']}";
          if(!$result = $conn->query($sql)) {
            exit($conn->error);
          }

          //Send otp to confirm reset password
          $mail = new PHPMailer();
          $mail->IsSMTP();
          $mail->Mailer = "smtp";


          $mail->SMTPDebug  = 1;
          $mail->SMTPAuth   = TRUE;
          $mail->SMTPSecure = "tls";
          $mail->Port       = 587;
          $mail->Host       = "smtp.gmail.com";
          $mail->Username   = "thanhtai762000@gmail.com";
          $mail->Password   = "thanhtai76";

          $mail->IsHTML(true);
          $mail->AddAddress($user['email'] , $user['user_name']);
          $mail->SetFrom("thanhtai762000@gmail.com", "WatchNow" /*"from-name"*/);
          //$mail->AddReplyTo("reply-to-email@domain", "reply-to-name");
          //$mail->AddCC("cc-recipient-email@domain", "cc-recipient-name");
          $mail->Subject = "Reset password???";
          $content = "<h3>Someone has requested to reset your password.<h3> <p>Here is a code for this requirement:</p> <strong>" . $confirm_code
          . "</strong><p>Please enter this code to the next page</p>";

          $mail->MsgHTML($content);
          if(!$mail->Send()) {
            echo "Error while sending Email.";
            //var_dump($mail);
          }

          header('location: ../users/enter_code.php');
        } else {
          $errors_confirm_email['confirm_fail'] = 'Email address not found!';
        }
    } else {
        $_SESSION['message'] = "Database error. Confirm email failed!";
        $_SESSION['type'] = "alert-danger";
    }
}



//Reset password
if(isset($_POST['done-btn'])) {
  $confirm_code = $_POST['code'];
  $query = "SELECT * FROM `users` WHERE `confirm_code`=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param('s', $confirm_code);

  if ($stmt->execute()) {
      $result = $stmt->get_result();
      if($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        //Generate a random password with the length is 10
        $new_password = trim(generatePassword(10));
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        //Update DB
        $query = "UPDATE `users` SET `password`='$hash' WHERE `id` = {$user['id']}";
        if (!$result = $conn->query($query)) {
            exit($conn->error);
        } else {
          //Send password
          $mail = new PHPMailer();
          $mail->IsSMTP();
          $mail->Mailer = "smtp";

          $mail->SMTPDebug  = 1;
          $mail->SMTPAuth   = TRUE;
          $mail->SMTPSecure = "tls";
          $mail->Port       = 587;
          $mail->Host       = "smtp.gmail.com";
          $mail->Username   = "thanhtai762000@gmail.com";
          $mail->Password   = "thanhtai76";

          $mail->IsHTML(true);
          $mail->AddAddress($user['email'] , $user['user_name']);
          $mail->SetFrom("thanhtai762000@gmail.com", "WatchNow" /*"from-name"*/);
          //$mail->AddReplyTo("reply-to-email@domain", "reply-to-name");
          //$mail->AddCC("cc-recipient-email@domain", "cc-recipient-name");
          $mail->Subject = "Here is your password. Please change it when login.";
          $content = "<strong>IMPORTANT!Please don't share it for anyone.</strong> <p>Your new password is " . $new_password . " </p>";

          $mail->MsgHTML($content);
          if(!$mail->Send()) {
            echo "Error while sending Email.";
            var_dump($mail);
          } else {
            echo "Email(Password) sent successfully";
            echo '<script>alert("Please Check Your Email for New Password")</script>';
          }
        }
        header('location: ../users/login.php');
      } else {
        $errors['confirm_fail'] = 'Wrong code! Please try again!';
      }
  } else {
      $_SESSION['message'] = "Database error. Login failed!";
      $_SESSION['type'] = "alert-danger";
  }
}











//Function get user details
function getUserDetails($id) {
  global $conn;
  $id = $conn->real_escape_string($id);
  $sql = "SELECT `user_name`, `email`, `password` FROM `users` WHERE `id` = $id";
  if(!$result = $conn->query($sql)) {
    exit($conn->error);
  }

  $data = [];

  if($result->num_rows > 0){
    while ($row = $result->fetch_assoc()) {
      $data = $row;
    }
  }
  return $data;
}



//Function change password
function verifyCurrentPassword($current_password, $password_hash) {
  return password_verify($current_password, $password_hash);
}
/**
* Change current password to new password
*
* @param [type] $id
* @param [type] $new_password
* @return void
*/
function changeCurrentPassword($id, $new_password) {
  global $conn;
  $id = $conn->real_escape_string($id);
  $password = $conn->real_escape_string($new_password);
  $password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 11]);

  $sql = "UPDATE `users` SET `password`='$password' WHERE `id`= '$id'";
  if(!$result = $conn->query($sql)) {
    exit($conn->error);
  }
  return true;
}

//Function generate a random password
function generatePassword($length = 8) {
  $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  $count = strlen($chars);

  for($i = 0, $result = ''; $i < $length; $i++) {
    $index = rand(0, $count -1);
    $result .= substr(str_shuffle($chars), $index, 1);
  }

  return $result;
}

//Function generate a random confirm code with the length of code is 9
function generateConfirmCode() {
  return rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9). rand(0, 9). rand(0, 9). rand(0, 9);
}



/*****************
******************
*****VIDEOS*******
******************
******************/




if (isset($_POST['post-video'])) {
  // // Check file size, type: mp4
  //   // TODO:
  //   //Reduce file before send to cloudinary
  // }
  // if($fileSize > 500000){
  //    die('<div class="alert alert-danger" role="alert"> File is too big </div>');


  $fileName = $_FILES['video']['name']; //  ["name"]=> string(20) "backgroundLaptop.jpg"
  $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
  $tmpName = $_FILES['video']['tmp_name']; // ["tmp_name"]=> string(24) "C:\xampp\tmp\phpB66B.tmp
  $fileType = $_FILES['video']['type']; //["type"]=> string(10) "image/jpeg"
  $fileSize = $_FILES['video']['size'];
  //["error"]=> int(0) ["size"]=> int(201003)


  $public_id = "my" . time();

  $title = $_POST['title'];
  $tags = $_POST['tags'];
  $userid = $_SESSION['id'];

  var_dump($_FILES['video']['error']);

  if($_FILES['video']['error'] > 0){
      echo 'There is an error in your file';
  }
  else{
    $query = "INSERT INTO `videos` SET `user_id`=?, `file_type`=?, `public_id`=?, `title`=?, `tags`=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('dssss', $userid, $fileType, $public_id, $title, $tags);
    $result = $stmt->execute();

    if($result) {
      \Cloudinary\Uploader::upload_large($tmpName,
            array(
                "resource_type" => "video",
                "public_id" => $public_id,
                "chunk_size" => 6_000_000
              ));
        echo 'success! File uploaded';
        header("location: ../videos/index.php");
    }
    else{
        echo 'error! File upload failed';
    }
  }
}
