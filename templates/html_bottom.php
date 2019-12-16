<?php
	
	if (!isset($verbete_vazio)) {
		$verbete_vazio = false;
	}
	if (!isset($mdb_select)) {
		$mdb_select = false;
	}
	if (!isset($gabarito)) {
		$gabarito = false;
	}
	if (!isset($texto_editar_titulo)) {
		$texto_editar_titulo = false;
	}
	if (!isset($etiquetas_bottom)) {
		$etiquetas_bottom = false;
	}
	if (!isset($etiquetas_bottom_adicionar)) {
		$etiquetas_bottom_adicionar = false;
	}
	if (!isset($biblioteca_bottom_adicionar)) {
		$biblioteca_bottom_adicionar = false;
	}
	if (!isset($esconder_introducao)) {
		$esconder_introducao = false;
	}
	if (!isset($sticky_toolbar)) {
		$sticky_toolbar = false;
	}
	if (!isset($hide_add_elements)) {
		$hide_add_elements = false;
	}
	
	echo "
    <!-- Bootstrap tooltips -->
    <script type='text/javascript' src='js/popper.min.js'></script>
    <!-- Bootstrap core JavaScript -->
    <script type='text/javascript' src='js/bootstrap.min.js'></script>
    <!-- MDB core JavaScript -->
    <script type='text/javascript' src='js/mdb.min.js'></script>
  ";
	
	if ($verbete_vazio == true) {
		echo "
		<script type='text/javascript'>
			$('#destravar_verbete').click(function () {
				$('#verbete_vazio').hide();
	    });
		</script>
		";
	}
	
	if ($mdb_select == true) {
		echo "
			<script type='text/javascript'>
				$(document).ready(function() {
					$('.mdb-select').materialSelect()
				});
			</script>
		";
	}
	
	if ($gabarito == true) {
		echo "
			<script type='text/javascript'>
				$('#mostrar_gabarito').click(function () {
					$('.list-group-item').removeClass('list-group-item-light');
					$('#mostrar_gabarito').hide();
		    });
			</script>
		";
	}
	if ($texto_editar_titulo == true) {
		echo "
			<script type='text/javascript'>
				$('input[name=novo_texto_titulo]').change(function() {
					var novo_texto_titulo = $('input[name=novo_texto_titulo').val();
					$.post('engine.php', {
						'novo_texto_titulo': novo_texto_titulo,
						'novo_texto_titulo_id': $texto_id
					}, function(data) {
					    if (data != 0) {
					    	$('#novo_texto_titulo').text(novo_texto_titulo);
					    }
						})
				});
			</script>
			";
	}
	if ($etiquetas_bottom == true) {
		echo "
      <script type='text/javascript'>
	      $(document).ready(function() {
	          $('#anotacao_salva').hide();
	          $('#salvar_anotacao a').click(function() {
	            $('#salvar_anotacao').hide();
	            $('#anotacao_salva').show();
	              $('#quill_trigger_{$texto_tipo}').click();
	          });
	      });
			  $('#buscar_etiquetas').keyup(function() {
			      var busca_etiquetas = $('#buscar_etiquetas').val();
			      var busca_etiquetas_length = $('#buscar_etiquetas').val().length;
			      if (busca_etiquetas_length > 2) {
			          $.post('engine.php', {
			              'busca_etiquetas': busca_etiquetas,
			              'busca_etiquetas_tipo': 'all'
			          }, function(data) {
			            if (data != 0) {
			                $('#etiquetas_disponiveis').empty();
			                $('#etiquetas_disponiveis').append(data);
			            }
			          });
			      }
			  });
			  $(document).on('click', '.adicionar_tag', function() {
			      var this_id = $(this).attr('value');
			      $(this).hide();
			      $.post('engine.php', {
			         'nova_etiqueta_id': this_id,
			         'nova_etiqueta_page_id': {$texto_id},
			         'nova_etiqueta_page_tipo': '{$texto_tipo}'
			      }, function(data) {
			         if (data != 0) {
			             $('#etiquetas_ativas').append(data);
			         }
			      });
			  });
			  $(document).on('click', '.remover_tag', function() {
			      var this_id = $(this).attr('value');
	              $(this).hide();
			      $.post('engine.php', {
			         'remover_etiqueta_id': this_id,
			         'remover_etiqueta_page_id': {$texto_id},
			         'remover_etiqueta_page_tipo': '{$texto_tipo}'
			      });
			  });
			  $(document).on('click', '#criar_etiqueta', function() {
		      var new_tag = $(this).attr('value');
		      $(this).hide();
		      $.post('engine.php', {
		         'criar_etiqueta_titulo': new_tag,
		         'criar_etiqueta_page_id': {$texto_id},
		         'criar_etiqueta_page_tipo': '{$texto_tipo}'
		      }, function(data) {
		         if (data != 0) {
		             $('#etiquetas_ativas').append(data);
		         }
		      });
		  });
      </script>
	";
	}
	if ($etiquetas_bottom_adicionar == true) {
		echo "
		<script type='text/javascript'>
			$('#buscar_etiquetas').keyup(function() {
				var busca_etiquetas = $('#buscar_etiquetas').val();
				var busca_etiquetas_length = $('#buscar_etiquetas').val().length;
				if (busca_etiquetas_length > 2) {
					$.post('engine.php', {
			              'busca_etiquetas': busca_etiquetas,
			              'busca_etiquetas_tipo': 'topico',
			              'busca_etiquetas_sem_link': 0
			          }, function(data) {
						if (data != 0) {
							$('#etiquetas_disponiveis').empty();
							$('#etiquetas_disponiveis').append(data);
						}
					});
	      }
			});
			$(document).on('click', '#criar_etiqueta', function() {
		      var new_tag = $(this).attr('value');
		      $(this).hide();
		      $.post('engine.php', {
		         'criar_etiqueta_titulo': new_tag,
		         'criar_etiqueta_page_id': {$texto_id},
		         'criar_etiqueta_page_tipo': '{$texto_tipo}'
		      }, function(data) {
		         if (data != 0) {
		             $('#etiquetas_disponiveis').prepend(data);
		         }
		      });
		  });
			$(document).on('click', '.adicionar_tag', function() {
			      var this_id = $(this).attr('value');
			      $(this).hide();
			      $.post('engine.php', {
			         'nova_etiqueta_id': this_id,
			         'nova_etiqueta_page_id': {$texto_id},
			         'nova_etiqueta_page_tipo': '{$texto_tipo}'
			      }, function(data) {
			         if (data != 0) {
			             $('#etiquetas_ativas').append(data);
			         }
			      });
			  });
		</script>
	";
	}
	if ($biblioteca_bottom_adicionar == true) {
		echo "
			<script type='text/javascript'>
				$('#criar_referencia_form').hide();
				$('#busca_referencias').keyup(function() {
				   var busca_referencias = $('#busca_referencias').val();
				   var busca_referencias_length = $('#busca_referencias').val().length;
				   if (busca_referencias_length > 2) {
				       $.post('engine.php', {
				           'busca_referencias': busca_referencias
				       }, function(data) {
				          if (data != 0) {
				              $('#referencias_disponiveis').show();
				              $('#criar_referencia_form').hide();
				              $('#referencias_disponiveis').empty();
				              $('#referencias_disponiveis').append(data);
				          }
				       });
				   }
				});
				$('#criar_referencia_autor').keyup(function() {
				    var criar_referencia_autor = $('#criar_referencia_autor').val();
				    var criar_referencia_autor_length = $('#criar_referencia_autor').val().length;
				    if (criar_referencia_autor_length > 2) {
							$.post('engine.php', {
							    'busca_autores': criar_referencia_autor
							}, function(data) {
					        $('#autores_disponiveis').empty();
							    if (data != 0) {
							        $('#autores_disponiveis').show();
							        $('#autores_disponiveis').append(data);
							    } else {
							        $('#autores_disponiveis').hide();
							    }
							})
				    }
				})
				$(document).on('click', '#criar_referencia', function() {
				    var nova_referencia = $(this).attr('value');
				    $(this).hide();
				    $('#referencias_disponiveis').hide();
				    $('#criar_referencia_form').show();
				})
/*				$(document).on('click', '.acrescentar_referencia_bibliografia', function() {
					var acrescentar_referencia = $(this).attr('value');
					$(this).hide();
					$.post('engine.php', {
					   'acrescentar_referencia_id': acrescentar_referencia
					}, function(data) {
						if (data != false) {
						    alert('sucesso');
						} else {
						    alert('insucesso');
						}
			   	});
				});*/
				$(document).on('click', '.adicionar_autor', function() {
				    var adicionar_autor = $(this).attr('value');
				    $(this).hide();
				    $('#criar_referencia_autor').val(adicionar_autor);
				    $('#autores_disponiveis').hide();
				})
				$(document).on('click', '#trigger_adicionar_referencia', function() {
				    var adicionar_referencia_titulo = $('#criar_referencia_titulo').val();
				    var adicionar_referencia_autor = $('#criar_referencia_autor').val();
				    var adicionar_referencia_tipo = $('#criar_referencia_tipo').val();
				    if ((adicionar_referencia_titulo != false) && (adicionar_referencia_autor != false) && (adicionar_referencia_tipo != false)) {
				        $.post('engine.php', {
				           'adicionar_referencia_titulo': adicionar_referencia_titulo,
				           'adicionar_referencia_autor': adicionar_referencia_autor,
				           'adicionar_referencia_tipo': adicionar_referencia_tipo
				        }, function(data) {
				            if (data != false) {
				                alert('Referência criada e adicionada a seu acervo.')
				            }
				        });
				    } else {
				        alert('Por favor, preencha todos os campos necessários.')
				    }
				});
			</script>
		";
	}
	if ($esconder_introducao == true) {
		echo "
			<script type='text/javascript'>
				function escritorio_initial() {
				    $('.esconder_sessao').hide();
				    $('.mostrar_sessao').show();
				}
				$(document).ready(function() {
				    escritorio_initial();
				});
				$(document).on('click', '#escritorio_home', function() {
				    escritorio_initial();
				    $('#pagina_usuario_informacoes').hide();
				});
				$(document).on('click', '#mostrar_textos', function() {
				    $('.esconder_sessao').hide();
				    $('#anotacoes_privadas').show();
				});
				$(document).on('click', '#mostrar_imagens', function() {
				    $('.esconder_sessao').hide();
				    $('#imagens_privadas').show();
				    $('#imagens_publicas').show();
				});
				$(document).on('click', '#mostrar_acervo', function() {
				    $('.esconder_sessao').hide();
				    $('#acervo_virtual').show();
				});
				$(document).on('click', '#mostrar_tags', function() {
				    $('.esconder_sessao').hide();
				    $('#topicos_interesse').show();
				});
				$(document).on('click', '#icone_simulados', function() {
				    $('.esconder_sessao').hide();
				    $('#sessao_simulados').show();
				    $('#respostas_usuario').show();
				    $('#sessao_plataforma_simulados').hide();
				});
				$(document).on('click', '#novo_adicionar_simulado', function() {
				    $('#sessao_plataforma_simulados').show();
				});
			</script>
		";
	}
	if ($sticky_toolbar == true) {
		echo "
		<script type='text/javascript'>
			$(document).ready(function() {
				$('.ql-toolbar').addClass('sticky-top bg-white');
	    });
		</script>
		";
	}
	if ($hide_add_elements == true) {
		echo "
			<script type='text/javascript'>
				$(document).ready(function() {
				    $('.add_elements').hide();
		    });
				$(document).on('click', '#show_add_elements', function() {
				    $('#show_add_elements').hide();
				    $('.add_elements').show();
				});
			</script>
		";
	}
?>