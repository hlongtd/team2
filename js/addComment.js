$(document).ready(function() {
  // var number_comments = $('')
  // getAllComments(0, )

  $('.btn-Add-Comment').on('click', function() {
    var id = $(this).attr("id");
    console.log(id);
    var arr = id.split("addComment");
    console.log(arr);
    var new_id = arr[1];
    console.log(new_id);
    var url = '../videos/addComment.php';
    console.log(url);

    var comment = $('#text-comment' + new_id).val();
    console.log(comment);

    if (comment.length > 5) {
      $.ajax({
        type: "POST",
        url: url,
        dataType: 'text',
        data: {
          addComment: 1,
          comment: comment,
          video_id: new_id
        },
        success: function(responsive) {
          console.log(responsive);
          //split to an array
          var split_arr = responsive.split("|");
          var totalComment = split_arr[0];
          //display total comments
          $('#btn-comment' + new_id).html("<img class='comment'  src='../img/chat.png'>")
          $('#btn-comment' + new_id).append(totalComment + ' Comment(s)');
          console.log($('#totalComment' + new_id));
          //display the comment
          $('#last-user-comment-img' + new_id).append("<img src='../img/user.png' style='height:40px;border-radius:100%;'>");
          $('#last-user-name' + new_id).text(split_arr[1]);
          $('#last-time' + new_id).text(split_arr[2]);
          $('#last-comment' + new_id).text(split_arr[3]);
          //
          $('#text-comment' + new_id).val('');


        }
      })
    } else {
      alert('Your comment must be > 5 characters');
    }
  });


  function getAllComments(start, max) {
    if (start > max) {
      return;
    }
    $.ajax({
      url: url,
      method: 'POST',
      dataType: 'text',
      data: {
        getAllComments: 1,
        start: start
      },
      success: function(responsive) {
        console.log(responsive);
        ('#display-comments' + new_id).append(responsive);
        getAllComments((start + 10), max);
      }
    });
  }

});