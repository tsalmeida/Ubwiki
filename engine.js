$(document).ready(function() {
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
	$('#add_bookmark').click(function() {
		$.post('engine.php', {
			'bookmark_change': true,
			'bookmark_tema_id': tema_id,
			'bookmark_user_id': user_id
		}, function(data) {
			if (data == true) {
				$('#add_bookmark').hide();
				$('#remove_bookmark').show();
			}
		});
		return false;
	});
	$('#remove_bookmark').click(function() {
		$.post('engine.php', {
			'bookmark_change': false,
			'bookmark_tema_id': tema_id,
			'bookmark_user_id': user_id
		}, function(data) {
			if (data == true) {
				$('#add_bookmark').hide();
				$('#remove_bookmark').show();
			}
		});
		return false;
	});
});
