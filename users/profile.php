<?php
require '../db/db.php';
//session_start();
// redirect user to login page if they're not logged in
$user_id = $_SESSION['id'];
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
    <title>WatchNow</title>
    <!-- <link rel="stylesheet" href="../css/bootstrap4/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/account_setting.css">
    <!-- <link rel="stylesheet" type="text/css" href="../css/profile.css"> -->


    <script src="../js/fontawesome/video_index.js" defer></script>
    <!-- <script src="https://kit.fontawesome.com/a076d05399.js" defer></script> -->

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
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#service">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item text-center mr-auto">
                        <div class="nav-link dropdown" >
                            <a data-toggle="dropdown" href="#imgOfUser"><img src="../img/user.png" style="height: 24px;" alt="User avatar"></a>
                            <a data-toggle="dropdown"><?php echo $_SESSION['user_name'];?>
                              <span><img src="../img/carret.png" style="height:20px;"></span>
                            </a>
                            <ul class="dropdown-menu text-center">
                              <li><a href="../users/profile.php">Profile</a></li>
                              <div class="dropdown-divider"></div>
                              <li><a class="dropdown-header">Setting</a></li>
                              <div class="dropdown-divider"></div>
                              <li><a href="../users/account_setting.php">Account Setting</a></li>
                              <li><a href="#privacy">Privacy</a></li>
                              <li><a href="#faqs">FAQs</a></li>
                              <li><a href="#terms">Terms & Conditions</a></li>
                              <div class="dropdown-divider"></div>
                              <li><a href="../users/logout.php"><span><img src="../img/logout.png" style="height:18px; margin-right:10px;"></span>Logout</a></li>
                            </ul>
                        </div>
                    <!--end .nav-item-->
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <img src="../img/mountain.png" alt="" width="100%" >

<div id="showcase" class="py-5">
    <div class="container">
        <form action="">
            <div class="float-right">
                <label for="file-upload" class="btn btn-dark">
                   Change Image
                </label>
                <input id="file-upload"  type="file" style="display:none;">
            </div>
        </form>
    </div>
</div>

 <div class="container">
    <div class="row">
            <div class="col-md-3 my-5 text-center" id="authors" >
                <div class="card">
                    <div class="card-body">
                        <img src="../img/user.png" alt="" class="img-fluid rounded-circle w-50 mb-3">
                        <h3><?php echo $_SESSION['user_name']; ?></h3>
                        <h5 class="text-muted">Lead Writer</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et nihil dolorem saepe eos reprehenderit ipsam!</p>

                        <div class="d-flex justify-content-center">
                            <div class="p-4">
                                <a href="http://facebook.com">
                                    <i class="fab fa-facebook"></i>
                                </a>
                            </div>
                            <div class="p-4">
                                <a href="http://twitter.com">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </div>
                            <div class="p-4">
                                <a href="http://instagram.com">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</div>


<!--video display-->
<div class="block-video">
                  <h2 class="text-center">WELCOME TO OUR SITE!</h2>
                  <video width="540" height="500" controls autoplay muted >
                      <source src="../img/facebooku.mp4" type="video/mp4" >
                  </video>
                  <div id="test">
                    <!--for debug ajax error-->
                  </div>
</div>

<div class="display-video">
                  <?php

                  $conn = new mysqli('localhost', 'root', '', 'watchnow');
                  if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                  }

                  /*get videos & who created it*/
                  $sql = "SELECT v.`user_id` as author_id, u.`user_name`, u.`email`, v.`id` AS video_id, v.`public_id`, v.`title`, v.`tags`, v.`like`, v.`date_uploaded`
                  FROM `videos` v, `users` u WHERE u.`id`=v.`user_id` ORDER BY v.`date_uploaded` DESC";
                  $result = $conn->query($sql);

                  while($row=$result->fetch_array()) {
                    //display like button: like or liked
                    $query = "SELECT * FROM `users_videos_like_map` WHERE `video_id`={$row['video_id']} and `user_id`='$user_id'";
                    $result_2 = $conn->query($query);
                    $rowCount = $result_2->num_rows;
                    //get total comment
                    $q_3 = "SELECT COUNT(*) AS total_comment FROM `comments` WHERE `video_id`={$row['video_id']}";
                    $result_3 = $conn->query($q_3);
                    $row_3 = $result_3->fetch_assoc();
                    //get comments detail
                    $q_4 = "SELECT u.`user_name`, c.`date_commented`, c.`content` FROM `users` u, `comments` c WHERE u.`id`=c.`user_id` AND c.`video_id`={$row['video_id']} ORDER BY c.`id` DESC LIMIT 3";
                    $result_4 = $conn->query($q_4);
                    $rowCount_4 = $result_4->num_rows;

                    echo "
                    <div class='block-video'>
                     <div id='video-" . $row['video_id'] ."'>
                      <div class='video-author'>
                        <img id='user-avatar' src='../img/user.png'>
                        <div class='user-name'>
                          <h3>" . $row['user_name'] . "</h3>
                          <span><img class='clock-icon' src='../img/clock.png'>" . $row['date_uploaded'] . "</span>
                        </div>
                      </div>

                      <div class='video-title'>
                        <h3>" . $row['title'] . "</h3>
                      </div>

                      <div class='video-content' style='cursor:pointer;'>
                        <video controls src='" . $url . $row['public_id'] . "'></video>
                      </div>

                      <div class='video-tag' style='display:inline-block'>
                        <a href='#tagsOfVideo'>#" . $row['tags'] . "</a>
                        <br><img src='../img/like-button.png' style='float:left;height:23px;margin-right:6px;border-radius:100%;cursor:pointer;'>
                        <div style='float:left' id='totallike" . $row['video_id'] . "' value='" . $row['like'] . "'>".$row['like']." Like(s)</div>
                      </div>

                      <div class='video-status-bar'>
                        <ul class='like-comment'>
                          <li>

                            ";
                            if($rowCount == 0) {
                              echo "
                              <svg class='black-heart' id='svg-like".$row['video_id']."' viewBox='0 0 32 29.6' style='cursor:pointer;'>
                                <path d='M23.6,0c-3.4,0-6.3,2.7-7.6,5.6C14.7,2.7,11.8,0,8.4,0C3.8,0,0,3.8,0,8.4c0,9.4,9.5,11.9,16,21.2
                                c6.1-9.3,16-12.1,16-21.2C32,3.8,28.2,0,23.6,0z'/>
                              </svg>
                              <a href='#' class='like' id='like".$row['video_id']."' title='Like' rel='Like'>
                                Like
                              </a>";
                            } else {
                              echo "
                              <svg class='red-heart' id='svg-like".$row['video_id']."' viewBox='0 0 32 29.6' style='cursor:pointer;'>
                                <path d='M23.6,0c-3.4,0-6.3,2.7-7.6,5.6C14.7,2.7,11.8,0,8.4,0C3.8,0,0,3.8,0,8.4c0,9.4,9.5,11.9,16,21.2
                                c6.1-9.3,16-12.1,16-21.2C32,3.8,28.2,0,23.6,0z'/>
                              </svg>
                              <a href='#' class='like' id='like".$row['video_id']."' title='Unlike' rel='Unlike'>
                                Liked
                              </a>";
                            }

                     echo "
                          </li>
                          <li>
                            <a href='#text-comment".$row['video_id']."' class='btn-comment-toogle' id='btn-comment".$row['video_id']."'>
                              <img class='comment' id='totalComment".$row['video_id']."' src='../img/chat.png'>" . $row_3['total_comment'] . " Comment(s)
                            </a>
                          </li>
                          <li>
                            <a href='#' class='view' ><img src='../img/view.png'>1 View(s)</a>
                          </li>
                        </ul>
                      <!--end of .video-status-bar-->
                      </div>


                      <div class='video-comments' id='video-comments".$row['video_id']."'>
                         <div class='input-comment'>
                            <div class='container'>
                               <div class='row'>
                                  <div class='col-1'>
                                     <img class='' src='../img/user.png' alt='User avatar'>
                                  </div>
                                  <div class='col-11'>
                                     <div class='content'>
                                        <div class='container'>
                                           <div >
                                             <textarea rows='2' id='text-comment".$row['video_id']."' class='comment-content' name='comment-content' placeholder=' Add your comment here..'></textarea>
                                           </div>
                                           <div>
                                             <button type='button' class='btn-Add-Comment btn btn-secondary' id='addComment".$row['video_id']."' style='float:right;'>Comment</button>
                                           </div>
                                        <!--end .container inside-->
                                        </div>
                                     <!--end .content-->
                                     </div>
                                  </div>
                               </div>
                            <!--end .container outside-->
                            </div>
                         <!--end .input-comment-->
                         </div>


                         <div class='display-comments' id='display-comments'".$row['video_id'].">
                            <div class='container' id='container".$row['video_id']."'>

                             <!--Temp display: Start: Display last user comment here-->
                             <div class='row'>
                              <div class='col-1 user-thumbnail'>
                                 <a href='#' id='last-user-comment-img".$row['video_id']."'></a>
                              </div>
                              <div class='col-11'>
                                 <div class='comment-details'>
                                    <div class='container'>
                                       <div class='comment-header'>
                                          <a href='#' class='user' id='last-user-name".$row['video_id']."'></a>
                                          <span class='text-muted time' style='font-size:12px;' id='last-time".$row['video_id']."'></span>
                                       </div>
                                       <div class='comment-text' id='last-comment".$row['video_id']."'>

                                       </div>
                                       <div class='comment-status-bar'>
                                          <a href='#replies'></a>
                                       </div>
                                    <!--end .container inside-->
                                    </div>
                                 <!--end .comment-details-->
                                 </div>

                              <!--end .col-11-->
                              </div>
                            <!--end .row-->
                            </div>
                            <!--Start: Display last user comment here-->";

                               if($rowCount_4 > 0) {
                                 while($row_4 = $result_4->fetch_array()) {
                               echo "
                                <div class='row'>
                                  <div class='col-1 user-thumbnail'>
                                     <a href='#'><img src='../img/user.png' style='height:40px;border-radius:100%;'></a>
                                  </div>
                                  <div class='col-11'>
                                     <div class='comment-details'>
                                        <div class='container'>
                                           <div class='comment-header'>
                                              <a href='#' class='user'>".$row_4['user_name']."</a>
                                              <span class='text-muted time' style='font-size:12px;'>".$row_4['date_commented']."</span>
                                           </div>
                                           <div class='comment-text'>
                                              ".$row_4['content']."
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
                                }//for while loop
                              } else {
                                    echo "";//empty comment
                                  }
                                echo "


                            <!--end .container outside-->
                            </div>
                         <!--end .display-comments-->
                         </div>";

                         if ($row_3['total_comment'] == 0) {
                           echo "";//disappear button More
                         } else {
                           echo "
                           <div class='load-more-btn '>
                              <button type='button' id='loadmore".$row['video_id']."' class='btn btn-secondary load-more'>More</button>
                           <!--end .load-more-btn -->
                           </div>";
                         }
                         echo "
                         <div id='load-more-message".$row['video_id']."' class='load-more-message'>

                         <!--end #load-more-message-id-->
                         </div>
                      <!--end .video-comments-->
                      </div>
                     <!--end #video-->
                     </div>
                    </div>
                    ";
                  }
                  ?>
                </div>


            <!--end .col-md-6-->
            </div>

            <div class="col-md-3" >
                <div class="card">
                    <div class="card-body">
                        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Omnis est voluptas ut excepturi dolores mollitia fuga eius velit repellendus labore! Unde possimus itaque recusandae animi totam, quas sunt, necessitatibus accusamus facere, quasi dicta eum eligendi distinctio modi ut! Sequi, optio natus. Maxime saepe eius ex quaerat optio accusamus iure quo!</p>
                    </div>
                </div>
            </div>
        <!--end .row-->
        </div>
    </div>

    <!-- Video Modal -->
    <div class="modal fade text-dark" id="videoModal">
      <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Post Your Video</h5>
          <button class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="index.php" method="post" enctype="multipart/form-data" >
            <div class="form-group ">
              <label for="name">Title</label>
              <input type="text" class="form-control" name="title" onfocus="this.value=''" required>
            </div>
            <div class="form-group">
              <label for="tags">Tags</label>
              <select class="form-control" id="tags" name="tags" required>
                <option value=""></option>
                <option value="Education">Education</option>
                <option value="Entertainment">Entertainment</option>
                <option value="Travel">Travel</option>
                <option value="Food">Food</option>
                <option value="Animal">Animal</option>
                <option value="News">News</option>

              </select>
            </div>
            <div class="form-group">
                <label for="post_image" class="d-block">Video</label>
                <input type="file" id="video" name="video" required>
            </div>
            <div >
                <button class="btn btn-dark btn-block" name="post-video">Submit</button>
            </div>
          </form>
        </div>

      </div>
    </div>
    </div>





    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

  <script src="../js/addLike.js"></script>
  <script src="../js/auto-resize-textarea.js"></script>
  <script src="../js/addComment.js"></script>
  <script src="../js/loadMoreComment.js"></script>
  <!-- <script src="../js/lazyLoading.js"></script> -->

 
    // <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
    //     integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
    //     crossorigin="anonymous"></script>
    // <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
    //     integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    //     crossorigin="anonymous"></script>
    // <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
    //     integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    //     crossorigin="anonymous"></script>

</body>

</html>
