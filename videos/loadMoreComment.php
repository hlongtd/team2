<?php
require 'db_connection.php';

if(isset($_POST['loadMore'])) {
  $start = $conn->real_escape_string($_POST['start']);
  $video_id = $conn->real_escape_string($_POST['video_id']);

  $q_3 = "SELECT COUNT(*) AS total_comment FROM `comments` WHERE `video_id`='$video_id'";
  $result_3 = $conn->query($q_3);
  $row_3 = $result_3->fetch_assoc();

  if($start >= $row_3['total_comment']) {
    echo "reachedMax";
  } else {

    $sql = "SELECT u.`user_name`, c.`date_commented`, c.`content` FROM `users` u, `comments` c WHERE u.`id`=c.`user_id` AND c.`video_id`='$video_id' ORDER BY c.`id` DESC LIMIT $start, 3";
    $result = $conn->query($sql);
    if(!$result) {
      exit($conn->error);
    }
    while($row = $result->fetch_array()) {
      echo "
       <div class='row'>
         <div class='col-1 user-thumbnail'>
            <a href='#'><img src='../img/user.png' style='height:40px;border-radius:100%;'></a>
         </div>
         <div class='col-11'>
            <div class='comment-details'>
               <div class='container'>
                  <div class='comment-header'>
                     <a href='#' class='user'>".$row['user_name']."</a>
                     <span class='text-muted time' style='font-size:12px;'>".$row['date_commented']."</span>
                  </div>
                  <div class='comment-text'>
                     ".$row['content']."
                  </div>
                  <div class='comment-status-bar'>
                     <a href='#'>REPLY</a>
                  </div>
               <!--end .container inside-->
               </div>
            <!--end .comment-details-->
            </div>


            <!--START: REPLIES COMMENTS-->
            <div>
               <div class='container'>
                  <div class='row'>
                     <div class='col-1 user-thumbnail'>
                        <a href='#'><img src='../img/user.png' style='height:40px;border-radius:100%;'></a>
                     <!--end .col-1 replies-->
                     </div>
                     <div class='col-11'>
                        <div class='comment-details'>
                           <div class='container'>
                              <div class='comment-header'>
                                 <a href='#' class='user'>Replier Name</a>
                                 <span class='text-muted time' style='font-size:12px;'>10 August 2020</span>
                              </div>
                              <div class='comment-text'>
                                 I am replier.
                              </div>
                              <div class='comment-status-bar'>
                                 <a href='#'>REPLY</a>
                              </div>
                           <!--end .container inside-->
                           </div>
                        <!--end .comment-details-->
                        </div>
                     <!--end .col-11 replies-->
                     </div>
                  <!--end .row of replies-->
                  </div>
               <!--end .container of replies-->
               </div>
            <!--end REPLIES COMMENTS-->
            </div>


         <!--end .col-11-->
         </div>
       <!--end .row-->
       </div>";
    }

  }



}
