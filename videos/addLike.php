<?php
require 'db_connection.php';

if(!empty($_POST['video_id']) && !empty($_POST['rel'])) {
  $video_id=$conn->real_escape_string($_POST['video_id']);
  $rel=$conn->real_escape_string($_POST['rel']);
  $user_id = $_SESSION['id'];
  // var_dump($user_id);

  if($rel == 'Like') {
    //Action Like
    $q1 = "SELECT `id` FROM `users_videos_like_map` WHERE `user_id`='$user_id' AND `video_id`='$video_id'";
    $result1 = $conn->query($q1);
    if($result1->num_rows == 0) {
      $q2 = "INSERT INTO `users_videos_like_map`(`user_id`,`video_id`) VALUES('$user_id','$video_id')";
      $result2 = $conn->query($q2);
      if(!$result2) {
        exit($conn->error);
      }
      $q3 = "UPDATE `videos` SET `like`=`like`+1 WHERE `id`='$video_id'";
      $result3 = $conn->query($q3);
      if(!$result3) {
        exit($conn->error);
      }
      $q4 = "SELECT `like` FROM `videos` WHERE `id`='$video_id'";
      $result4 = $conn->query($q4);
      if(!$result4) {
        exit($conn->error);
      }
      $row = $result4->fetch_assoc();
      echo $row['like'];
    }
  } else if($rel=='Unlike') {

    // Action Unlike
    $q1 = "SELECT `id` FROM `users_videos_like_map` WHERE `user_id`='$user_id' AND `video_id`='$video_id'";
    $result1 = $conn->query($q1);
    if ($result1->num_rows > 0) {
      $q2 = "DELETE FROM `users_videos_like_map` WHERE `user_id`='$user_id' AND `video_id`='$video_id'";
      $result2 = $conn->query($q2);
      if(!$result2) {
        exit($conn->error);
      }
      $q3 = "UPDATE `videos` SET `like`=`like`-1 WHERE `id`='$video_id'";
      $result3 = $conn->query($q3);
      if(!$result3) {
        exit($conn->error);
      }
      $q4 = "SELECT `like` FROM `videos` WHERE `id`='$video_id'";
      $result4 = $conn->query($q4);
      if(!$result4) {
        exit($conn->error);
      }
      $row = $result4->fetch_assoc();
      echo $row['like'];
    }
  }

}
