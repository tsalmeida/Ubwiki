$(document).ready(function() {
  $(document.body).on('click', '.cardmateria' ,function(){
    if ($(this).attr("href")) {
      var link = $(this).attr("href");
      window.open(link, '_self');
      event.preventDefault();
    }
  });
});
