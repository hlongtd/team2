<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'watchnow');
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $query = "SELECT * FROM users WHERE token=? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $token);
    // var_dump($token);

    if ($stmt->execute()) {
      $result = $stmt->get_result();
      var_dump($result);
      if ($result->num_rows > 0) {
          $user = $result->fetch_assoc();
          $query = "UPDATE users SET verified=1 WHERE token='$token'";

          if ($conn->query($query)) {
              $_SESSION['id'] = $user['id'];
              $_SESSION['username'] = $user['user_name'];
              $_SESSION['email'] = $user['email'];
              $_SESSION['verified'] = true;
              $_SESSION['message'] = "Your email address has been verified successfully";
              $_SESSION['type'] = 'alert-success';
              header('location: ../index.php'); /*change directory*/
              exit(0);
          }
      } else {
          echo "User not found!";
      }
    }
} else {
    echo "No token provided!";
}
