$(document).ready(function() {
  function init() {
    var vidDefer = document.getElementsByTagName('video');
    console.log(vidDefer.length);
    for (var i = 0; i < vidDefer.length; i++) {

      console.log(vidDefer[i].getAttribute('data-src'));
      if (vidDefer[i].getAttribute('data-src')) {
        vidDefer[i].setAttribute('src', vidDefer[i].getAttribute('data-src'));
      }
    }
  }
  window.onload = init;



});