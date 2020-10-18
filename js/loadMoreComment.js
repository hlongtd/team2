$(document).ready(function() {
  var start = 3;

  $('.load-more').click(function() {
    var id = $(this).attr("id");
    console.log(id);
    var sid = id.split("loadmore");
    var video_id = sid[1];
    console.log(video_id);


    $.ajax({
      type: "POST",
      url: '../videos/loadMoreComment.php',
      dataType: 'text',
      data: {
        loadMore: 1,
        start: start,
        video_id: video_id
      },
      beforeSend: function() {
        $('#load-more-message' + video_id).html('Loading ...');
      },
      success: function(response) {
        console.log(response);
        if (response == 'reachedMax') {
          $('#load-more-message' + video_id).html('');
          $('#load-more-message' + video_id).append("No More Found!").hide().show('slow');
        } else {
          $('#load-more-message' + video_id).html('');
          $('#load-more-message' + video_id).append("<img src='../img/upload.png'>");
          $('#container' + video_id).append(response).hide().show('slow');
          start = start + 3;
        }

      }
    });

  });
});