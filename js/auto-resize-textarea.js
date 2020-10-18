$(document).ready(function() {
  $('textarea').each(function() {
    this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
  }).on('input', function() {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
  });

  $('.btn-comment-toogle').click(function() {
    var id = $(this).attr("id");
    console.log(id);
    var sid = id.split("btn-comment");
    var video_id = sid[1];
    console.log(video_id);


    $('#video-comments' + video_id).css('display', 'block');
  });



});