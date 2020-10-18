$(document).ready(function() {
  $(".like").click(function() {

    var ID = $(this).attr("id");
    console.log(ID);
    //split id to an array
    var sid = ID.split("like");
    console.log(sid);
    //get the id
    var New_ID = sid[1];
    console.log(New_ID);
    //get action: like or unlike
    var REL = $(this).attr("rel");
    console.log(REL);
    var URL = '../videos/addLike.php';
    console.log(URL);
    var dataString = 'video_id=' + New_ID + '&rel=' + REL;
    console.log(dataString);
    $.ajax({
      type: "POST",
      url: URL,
      data: dataString,
      cache: false,
      success: function(responsive) {
        if (REL == 'Like') {
          console.log(responsive);
          // var like_count = responsive.split("\n");
          // console.log(like_count[1]);
          $('#totallike' + New_ID).text(responsive + ' Like(s)');
          $('#' + ID).html("Liked").attr('rel', 'Unlike').attr('title', 'Unlike');
          console.log($('#' + ID));
          $('#svg-like' + New_ID).removeClass('black-heart');
          $('#svg-like' + New_ID).addClass('red-heart');

        } else {
          console.log(responsive);
          // var like_count = responsive.split("\n");
          // console.log(like_count[1]);
          $('#totallike' + New_ID).text(responsive + ' Like(s)');
          $('#' + ID).attr('rel', 'Like').attr('title', 'Like').html("Like");
          // console.log($('#' + ID));
          $('#svg-like' + New_ID).removeClass('red-heart');
          $('#svg-like' + New_ID).addClass('black-heart');

        }
      }
    })
  })
});