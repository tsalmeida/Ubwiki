$(document).ready(function() {

  $(document.body).on('click', '.cardmateria' ,function(){
    if ($(this).attr("href")) {
      var link = $(this).attr("href");
      window.open(link, '_self');
      event.preventDefault();
    }
  });
  $('#searchBarGo').click(function() {
    var command = $('#searchBar').val();
    var command = btoa(command);
    var concurso = $('#searchBarGo').val();
    var concurso = btoa(concurso);
    $.post('engine.php', {'sbcommand': command, 'sbconcurso': concurso}, function(data) {
      $("#searchBar").val('');
      if (data != 0) {
        data = btoa(data);  
        $("#searchBar").val(data);
      }
    });
    return false;
  });
});
