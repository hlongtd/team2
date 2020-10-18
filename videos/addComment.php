<?php
require 'db_connection.php';

if(isset($_POST['addComment'])) {
  $comment = $conn->real_escape_string($_POST['comment']);
  $video_id = $conn->real_escape_string($_POST['video_id']);
  $user_id = $_SESSION['id'];

  $q = "INSERT INTO `comments`(`user_id`,`video_id`,`content`) VALUES('$user_id','$video_id','$comment')";
  $result = $conn->query($q);
  if(!$result) {
    exit($conn->error);
  }

  $last_id = $conn->insert_id;
  $arr = array();

  $q1 = "SELECT COUNT(*) AS `total_Comment` FROM `comments` WHERE `video_id`='$video_id'";
  $result1 = $conn->query($q1);
  if(!$result1) {
    exit($conn->error);
  }
  $row1 = $result1->fetch_assoc();

  $q2 = "SELECT u.`user_name`, c.`date_commented`, c.`content` FROM `users` u, `comments` c WHERE u.`id`='$user_id' AND u.`id`=c.`user_id` AND c.`video_id`='$video_id' ANd c.`id`=$last_id ORDER BY c.`date_commented` DESC";
  $result2 = $conn->query($q2);
  if(!$result2) {
    exit($conn->error);
  }
  $row2 = $result2->fetch_assoc();
  $array = array($row1['total_Comment'], $row2['user_name'], $row2['date_commented'], $row2['content']);

  echo implode("|", $array);

}
