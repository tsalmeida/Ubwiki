$(document).ready(function() {
	$(".hidewhen").hide();
	$(document.body).on('click', '.anotacoes_down', function(){
		$('.anotacoes_up').show();
		$('.anotacoes_down').hide()
	})
	$(document.body).on('click', '.anotacoes_up', function(){
		$('.anotacoes_up').hide();
		$('.anotacoes_down').show()
	})
	$("#searchBar").focus();
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
        var pw = data.substring(0, 16);
        var pw2 = data.substring(16);
        if (pw == 'notfoundnotfound') {
          $("#searchBar").val(pw2);
        }
        else if (pw = 'foundfoundfoundf') {
          window.open(pw2, '_self');
        }
      }
    });
    return false;
  });
});
