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
	if (!isset($sistema_etiquetas_elementos)) {
		$sistema_etiquetas_elementos = false;
	}
	if (!isset($sistema_etiquetas_topicos)) {
		$sistema_etiquetas_topicos = false;
	}
	if (!isset($carregar_adicionar_materia)) {
		$carregar_adicionar_materia = false;
	}
	if (!isset($carregar_adicionar_topico)) {
		$carregar_adicionar_topico = false;
	}
	if (!isset($carregar_adicionar_subtopico)) {
		$carregar_adicionar_subtopico = false;
	}
	if (!isset($carregar_convite)) {
		$carregar_convite = false;
	}
	if (!isset($bottom_compartilhar_usuario)) {
		$bottom_compartilhar_usuario = false;
	}
	if (!isset($esconder_botao_determinar_acesso)) {
		$esconder_botao_determinar_acesso = false;
	}
	if (!isset($carregar_toggle_acervo)) {
		$carregar_toggle_acervo = false;
	}
	if (!isset($carregar_toggle_paginas_livres)) {
		$carregar_toggle_paginas_livres = false;
	}
	if (!isset($area_interesse_ativa)) {
		$area_interesse_ativa = false;
	}
	if (!isset($carregar_remover_usuarios)) {
		$carregar_remover_usuarios = false;
	}
	if (!isset($carregar_notificacoes)) {
		$carregar_notificacoes = false;
	}
	if (!isset($carregar_controle_estado)) {
		$carregar_controle_estado = false;
	}
	if (!isset($carregar_modal_login)) {
		$carregar_modal_login = false;
	}
	if ($user_id == false) {
		$carregar_modal_login = true;
	}

	if (!isset($quill_was_loaded)) {
		$quill_was_loaded = false;
	}

	if (!isset($hide_and_show_wallet_form)) {
		$hide_and_show_wallet_form = false;
	}
	if (!isset($carregar_toggle_curso)) {
		$carregar_toggle_curso = false;
	}
	if (!isset($pagina_padrao)) {
		$pagina_padrao = false;
	}
	if (!isset($loaded_correcao_form)) {
		$loaded_correcao_form = false;
	}

	if (!isset($load_change_into_model)) {
		$load_change_into_model = false;
	}

	if (!isset($open_review_modal)) {
		$open_review_modal = false;
	}

	if (!isset($carregar_publicar_modelo)) {
		$carregar_publicar_modelo = false;
	}

	if (!isset($pagina_subtipo)) {
		$pagina_subtipo = false;
	}

	if (!isset($pagina_compartilhamento_por_link)) {
		$pagina_compartilhamento_por_link = false;
	}

	if (!isset($trigger_criar_simulado)) {
		$trigger_criar_simulado = false;
	}

	if (!isset($modal_pagina_dados)) {
		$modal_pagina_dados = false;
	}

	if (!isset($anotacoes_col)) {
		$anotacoes_col = false;
	}

	if (!isset($anotacoes_existem)) {
		$anotacoes_existem = false;
	}

	if (!isset($carregar_secoes)) {
		$carregar_secoes = false;
	}

//	if ($mdb_select == true) {
//		echo "
//			<script type='text/javascript'>
//				$(document).ready(function() {
//					$('.mdb-select').materialSelect()
//				});
//			</script>
//		";
//	}

	echo "
		<!-- Bootstrap 5 -->
        <script src=\"https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js\" integrity=\"sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM\" crossorigin=\"anonymous\"></script>
	";

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

// esse mecanismo precisa ser dinâmico o bastante para que funcione tanto para etiquetas de
// elementos quanto para etiquetas de tópicos
	if (($sistema_etiquetas_topicos == true) || ($sistema_etiquetas_elementos == true)) {
		echo "
			<script type='text/javascript'>
				$(document).on('click', '.adicionar_tag', function() {
			      var this_id = $(this).attr('value');
			      $(this).hide();
			      $.post('engine.php', {
			         'nova_etiqueta_id': this_id,
			         'nova_etiqueta_page_id': {$pagina_id},
			         'nova_etiqueta_page_tipo': '{$pagina_tipo}'
			      }, function(data) {
			         if (data != 0) {
			             if (data == 1) {
											window.location.reload(true);
			             }
			             else {
				           		$('#etiquetas_ativas').append(data);
			             }
			         }
			      });
			  });
			</script>
		";
	}
	if ($sistema_etiquetas_elementos == true) {
		echo "
			<script type='text/javascript'>
					$('#criar_referencia_form').hide();
					$('#trigger_adicionar_video').click(function() {
						$('#criar_referencia_tipo').val('video');
					})
					$('#trigger_adicionar_livro').click(function() {
						$('#criar_referencia_tipo').val('referencia');
					})
					$('#trigger_adicionar_album_musica').click(function() {
						$('#criar_referencia_tipo').val('album_musica');
					})
					$('#trigger_adicionar_imagem').click(function() {
					    $('#criar_referencia_tipo').val('imagem');
					})
					$('#trigger_buscar_referencias').click(function() {
				   var busca_referencias_tipo = $('#criar_referencia_tipo').val();
					 var busca_referencias = $('#busca_referencias').val();
				   var busca_referencias_length = $('#busca_referencias').val().length;
				   if (busca_referencias_length > 2) {
				       $.post('engine.php', {
				           'busca_referencias': busca_referencias,
				           'busca_referencias_tipo': busca_referencias_tipo
				       }, function(data) {
				          if (data != 0) {
				              $('#referencias_disponiveis').show();
				              $('#criar_referencia_form').hide();
				              $('#referencias_disponiveis').empty();
				              $('#referencias_disponiveis').append(data);
				          }
				       });
				   }
					}),
					$('#trigger_buscar_autores').click(function() {
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
				}),
				$(document).on('click', '#criar_referencia', function() {
				    var criar_referencia_tipo = $('#criar_referencia_tipo').val();
				    if (criar_referencia_tipo != 'imagem') {
				    	$(this).hide();
				    	$('#referencias_disponiveis').hide();
				    	$('#criar_referencia_form').show();
				    } else {
				        $('#modal_buscar_elemento').modal('toggle');
				        $('#modal_adicionar_imagem').modal('toggle');
				    }
				}),
				$(document).on('click', '.adicionar_autor', function() {
				    var adicionar_autor = $(this).attr('value');
				    $(this).hide();
				    $('#criar_referencia_autor').val(adicionar_autor);
				    $('#autores_disponiveis').hide();
				}),
				$(document).on('click', '#trigger_adicionar_referencia', function() {
				    var adicionar_referencia_titulo = $('#criar_referencia_titulo').val();
				    var adicionar_referencia_link = $('#criar_referencia_link').val();
				    var adicionar_referencia_autor = $('#criar_referencia_autor').val();
				    var adicionar_referencia_tipo = $('#criar_referencia_tipo').val();
				    var adicionar_referencia_subtipo = $('#criar_referencia_subtipo').val();
				    if ((adicionar_referencia_titulo != false) && (adicionar_referencia_tipo != false)) {
				        $.post('engine.php', {
				           'adicionar_referencia_titulo': adicionar_referencia_titulo,
				           'adicionar_referencia_autor': adicionar_referencia_autor,
				           'adicionar_referencia_tipo': adicionar_referencia_tipo,
				           'adicionar_referencia_link': adicionar_referencia_link,
				           'adicionar_referencia_subtipo': adicionar_referencia_subtipo,
				           'adicionar_referencia_contexto': '$pagina_tipo',
				           'adicionar_referencia_pagina_id': $pagina_id
				        }, function(data) {
				            if (data != false) {
				                window.location.reload(true);
				            }
				        });
				    } else {
				        alert('{$pagina_translated['Por favor, preencha todos os campos necessários.']}')
				    }
				});
					$(document).on('click', '.selecionar_subcategoria', function() {
							var subcategoria_value = $(this).attr('value');
							$('#criar_referencia_subtipo').val(subcategoria_value);
							$('#nova_imagem_subtipo').val(subcategoria_value);
					});
					$(document).on('click', '.selecionar_categoria', function() {
					    var categoria_value = $(this).attr('value');
					    $('.subcategorias').addClass('d-none');
					    if (categoria_value == 'imagem') {
					        $('.subcategoria_imagem').removeClass('d-none');
					    } else if (categoria_value == 'video') {
					        $('.subcategoria_video').removeClass('d-none');
					    } else if (categoria_value == 'referencia') {
					        $('.subcategoria_referencia').removeClass('d-none');
					    } else if (categoria_value == 'album_musica') {
					        $('.subcategoria_album_musica').removeClass('d-none');
					    }
					});
					$(document).on('click', '#add_elements', function() {
					   $('.subcategorias').removeClass('d-none');
					});
					$(document).on('click', '#trigger_listar_youtube', function() {
					    $('#modal_adicionar_youtube').toggle();
					});
					$(document).on('click', '#elemento_subtipo', function() {
					   $('.subcategorias').removeClass('d-none');
					});
			</script>
		";
	}
	if ($carregar_adicionar_materia == true) {
		echo "
			<script type='text/javascript'>
				$('#trigger_buscar_materias').click(function() {
				   var busca_materias = $('#buscar_materias').val();
				   var busca_materias_length = $('#buscar_materias').val().length;
				   if (busca_materias_length > 2) {
				       $.post('engine.php', {
				       		'busca_etiquetas': busca_materias,
				       		'busca_etiquetas_tipo': 'topico',
				       		'busca_etiquetas_contexto': '$pagina_tipo'
				       }, function(data) {
				          if (data != 0) {
				              $('#materias_disponiveis').empty();
				              $('#materias_disponiveis').append(data);
				          }
				       });
				   }
				});
				$(document).on('click', '.adicionar_materia', function() {
			      var this_id = $(this).attr('value');
			      $(this).removeClass('bg-warning-light');
			      $(this).addClass('bg-success-light');
			      $.post('engine.php', {
			         'curso_nova_materia_id': this_id,
			         'curso_nova_materia_pagina_id': $pagina_id,
			         'curso_nova_materia_user_id': $user_id
			      }, function(data) {
			         if (data != 0) {
			             $('#buscar_materias').val('');
			             $('#buscar_materias').focus();
			         }
			      });
			  });
				$(document).on('click', '#criar_materia', function() {
		      var new_tag = $(this).attr('value');
		      $(this).removeClass('btn-success');
		      $(this).addClass('btn-light');
		      $(this).prop('disabled', true);
		      $.post('engine.php', {
		         'criar_materia_titulo': new_tag,
		         'criar_materia_page_id': {$pagina_id},
		         'criar_materia_page_tipo': '{$pagina_tipo}'
		      }, function(data) {
		         if (data != 0) {
		             $('#buscar_materias').val('');
		             $('#buscar_materias').focus();
		         }
		      });
		  	});
			</script>
			
		";
	}
	if ($carregar_adicionar_topico == true) {
		echo "
			<script type='text/javascript'>
			$('#trigger_buscar_topicos').click(function() {
				var busca_topicos = $('#buscar_topicos').val();
				var busca_topicos_length = $('#buscar_topicos').val().length;
				if (busca_topicos_length > 2) {
					$.post('engine.php', {
						'busca_etiquetas': busca_topicos,
						'busca_etiquetas_tipo': 'topico',
						'busca_etiquetas_contexto': 'materia'
						}, function(data) {
						if (data != 0) {
							$('#topicos_disponiveis').empty();
							$('#topicos_disponiveis').append(data);
						}
					});
				}
			});
			$(document).on('click', '.adicionar_topico', function() {
				$(this).prop('disabled', true);
				var this_id = $(this).attr('value');
				$(this).removeClass('bg-warning-light');
				$(this).addClass('bg-success-light');
				$.post('engine.php', {
					'curso_novo_topico_id': this_id,
					'curso_novo_topico_pagina_id': $pagina_id,
					'curso_novo_topico_user_id': $user_id
				}, function(data) {
					if (data != 0) {
					$('#buscar_topicos').val('');
					$('#buscar_topicos').focus();
					}
				});
			});
			$(document).on('click', '#criar_topico', function() {
				var new_tag = $(this).attr('value');
				$(this).removeClass('btn-success');
				$(this).addClass('btn-light');
				$(this).prop('disabled', true);
				$.post('engine.php', {
					'criar_topico_titulo': new_tag,
					'criar_topico_page_id': {$pagina_id},
					'criar_topico_page_tipo': '{$pagina_tipo}'
				}, function(data) {
					if (data != 0) {
					$('#buscar_topicos').val('');
					$('#buscar_topicos').focus();
					}
				});
			});
			</script>
			
		";
	}
	if ($carregar_adicionar_subtopico == true) {
		echo "
			<script type='text/javascript'>
				$('#trigger_buscar_subtopicos').click(function() {
				   var busca_subtopicos = $('#buscar_subtopicos').val();
				   var busca_subtopicos_length = $('#buscar_subtopicos').val().length;
				   if (busca_subtopicos_length > 2) {
				       $.post('engine.php', {
				       		'busca_etiquetas': busca_subtopicos,
				       		'busca_etiquetas_tipo': 'topico',
				       		'busca_etiquetas_contexto': 'topico'
				       }, function(data) {
				          if (data != 0) {
				              $('#subtopicos_disponiveis').empty();
				              $('#subtopicos_disponiveis').append(data);
				          }
				       });
				   }
				});
				$(document).on('click', '.adicionar_subtopico', function() {
			      var this_id = $(this).attr('value');
			      $(this).removeClass('bg-warning-light');
			      $(this).addClass('bg-success-light');
			      $.post('engine.php', {
			         'curso_novo_subtopico_id': this_id,
			         'curso_novo_subtopico_pagina_id': $pagina_id,
			         'curso_novo_subtopico_user_id': $user_id
			      }, function(data) {
			         if (data != 0) {
		             $('#buscar_subtopicos').val('');
		             $('#buscar_subtopicos').focus();
			         }
			      });
			  });
				$(document).on('click', '#criar_subtopico', function() {
		      var new_tag = $(this).attr('value');
		      $(this).removeClass('btn-success');
		      $(this).addClass('btn-light');
		      $(this).prop('disabled', true);
		      $.post('engine.php', {
		         'criar_subtopico_titulo': new_tag,
		         'criar_subtopico_page_id': {$pagina_id},
		         'criar_subtopico_page_tipo': '{$pagina_tipo}'
		      }, function(data) {
		         if (data != 0) {
		             $('#buscar_subtopicos').val('');
		             $('#buscar_subtopicos').focus();
		         }
		      });
		  	});
			</script>
			
		";
	}
	if ($sistema_etiquetas_topicos == true) {
		echo "
      <script type='text/javascript'>
				$('#trigger_buscar_etiquetas').click(function() {
			      var busca_etiquetas = $('#buscar_etiquetas').val();
			      var busca_etiquetas_length = $('#buscar_etiquetas').val().length;
			      if (busca_etiquetas_length > 2) {
			          $.post('engine.php', {
			              'busca_etiquetas': busca_etiquetas,
			              'busca_etiquetas_tipo': 'topico',
			              'busca_etiquetas_contexto': 'pagina'
			          }, function(data) {
			            if (data != 0) {
			                $('#etiquetas_disponiveis').empty();
			                $('#etiquetas_disponiveis').append(data);
			            }
			          });
			      }
			  });
			  $(document).on('click', '.remover_tag', function() {
			      var this_id = $(this).attr('value');
			      $(this).addClass('d-none');
			      $.post('engine.php', {
			         'remover_etiqueta_id': this_id,
			         'remover_etiqueta_page_id': {$pagina_id},
			         'remover_etiqueta_page_tipo': '{$pagina_tipo}'
			      });
			  });
			  $(document).on('click', '#criar_etiqueta', function() {
		      var new_tag = $(this).attr('value');
		      $(this).removeClass('btn-success');
		      $(this).addClass('btn-warning');
		      $(this).prop('disabled', true);
		      $.post('engine.php', {
		         'criar_etiqueta_titulo': new_tag,
		         'criar_etiqueta_page_id': {$pagina_id},
		         'criar_etiqueta_page_tipo': '{$pagina_tipo}'
		      }, function(data) {
		         if (data != 0) {
		             $('#etiquetas_ativas').append(data);
		         }
		      });
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
				    $('#paginas_usuario').show();
				    $('#anotacoes_privadas').show();
				});
				$(document).on('click', '#mostrar_imagens', function() {
				    $('.esconder_sessao').hide();
				    $('#imagens_publicas').show();
				    $('#imagens_privadas').show();
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
				$(document).on('click', '#mostrar_grupos', function() {
				    $('.esconder_sessao').hide();
				    $('#grupos_estudos').show();
				    $('#convidar_grupo').show();
				    $('#convites_ativos').show();
				    $('#criar_grupo').show();
				    $('#grupos_usuario_membro').show();
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
					$('.ql-toolbar').addClass('sticky-top bg-light text-white border rounded border-0 mt-1');
		    });
			</script>
		";
	}
	if (isset($quill_extra_buttons)) {
		echo "
			<script type='text/javascript'>
			 $(document).ready(function() {
					var extra_buttons = \"$quill_extra_buttons\";
					$('.ql-toolbar').append(extra_buttons);
	      });
			</script>
		";
	}
	if (isset($adicionar_tag_pagina)) {
		echo "
			<script type='text/javascript'>
				$(document).on('click', '.adicionar_tag', function() {
			      var this_id = $(this).attr('value');
			      $(this).hide();
			      $.post('engine.php', {
			         'pagina_nova_etiqueta_id': this_id,
			         'nova_etiqueta_pagina_id': $pagina_id,
			      }, function(data) {
			         if (data != 0) {
			             $('#etiquetas_ativas').append(data);
			         }
			      });
			  });
			</script>
		";
	}

	if ($carregar_convite == true) {
		echo "
			<script type='text/javascript'>
				$(document).on('click', '#trigger_buscar_convidado', function() {
				    convidado_busca = $('#apelido_convidado').val();
				    convidado_grupo_id = '$grupo_id';
				    $.post('engine.php', {
				    	'busca_apelido': convidado_busca,
				    	'busca_grupo_id': convidado_grupo_id
				    }, function(data) {
				       if (data != 0) {
				       		$('#convite_resultados').empty();
				       		$('#convite_resultados').append(data);
				       	}
				    });
				});
				$(document).on('click', '.convite_usuario', function() {
				    var usuario_id = $(this).attr('value');
            $(this).hide();
				    $.post('engine.php', {
				       'convidar_usuario_id': usuario_id,
				       'convidar_grupo_id': $grupo_id
				    }, function(data) {
				        if (data != 0) {
				            alert('Convite enviado');
				        }
				    });
				});
			</script>
		";
	}

	if ($bottom_compartilhar_usuario == true) {
		echo "
		<script type='text/javascript'>
		$(document).on('click', '#trigger_buscar_convidado_compartilhamento', function() {
			convidado_busca_compartilhamento = $('#apelido_convidado_compartilhamento').val();
			convidado_pagina_id = '$pagina_id';
			$.post('engine.php', {
				    	'busca_apelido': convidado_busca_compartilhamento,
				    	'busca_pagina_id': convidado_pagina_id
				    }, function(data) {
				if (data != 0) {
					$('#convite_resultados_compartilhamento').empty();
					$('#convite_resultados_compartilhamento').append(data);
				}
			});
				});
		$(document).on('click', '.compartilhar_usuario', function() {
			var usuario_id = $(this).attr('value');
			$(this).hide();
			$.post('engine.php', {
				       'compartilhar_usuario_id': usuario_id,
				       'compartilhar_pagina_id': {$pagina_id}
				    }, function(data) {
				if (data != 0) {
					alert('Compartilhamento efetuado');
				}
			});
		});
		$(document).on('click', '.remover_compartilhamento_usuario', function() {
		    remover_compartilhamento_usuario = $(this).attr('value');
		    $(this).hide();
		    $.post('engine.php', {
		        'remover_compartilhamento_usuario': remover_compartilhamento_usuario,
		        'remover_compartilhamento_usuario_pagina': {$pagina_id}
		    }, function(data) {
		        if (data != 0) {
		            alert('{$pagina_translated['O acesso do usuário a esta página foi revogado.']}');
		        }
		    });
		});
		$(document).on('click', '.remover_acesso_grupo', function() {
		    remover_acesso_grupo = $(this).attr('value');
		    $(this).hide();
		    $.post('engine.php', {
		       'remover_acesso_grupo': remover_acesso_grupo,
		       'remover_acesso_grupo_pagina_id': {$pagina_id}
		    }, function(data) {
		        if (data != 0) {
		            alert('{$pagina_translated['O acesso desse grupo de estudos a esta página foi revogado.']}');
		        }
		    });
		})
		$(document).on('click', '.radio_publicar_opcao', function() {
			publicar_opcao = $(this).val();
			if (publicar_opcao == 'ubwiki') {
			    $('.botao_determinar_acesso').hide();
			} else {
			    $('.botao_determinar_acesso').show();
			}
	    $('#form_modal_compartilhar_pagina').submit();
		});
		$(document).on('click', '.colaboracao_opcao', function() {
			colaboracao_opcao = $(this).val();
			if (colaboracao_opcao == 'selecionada') {
			    $('.botao_determinar_colaboracao').show();
			} else {
			    $('.botao_determinar_colaboracao').hide();
			}
      $('#form_modal_compartilhar_pagina').submit();
		});
	</script>
	";
	}
	if (isset($pagina_colaboracao)) {
		if ($pagina_colaboracao != 'selecionada') {
			echo "
			<script type='text/javascript'>
				$('.botao_determinar_colaboracao').hide();
			</script>
		";
		}
	}
	if ($esconder_botao_determinar_acesso == true) {
		echo "
			<script type='text/javascript'>
				$('.botao_determinar_acesso').hide();
			</script>
		";
	}
	if ($carregar_toggle_acervo == true) {
		if ($item_no_acervo == true) {
			$apagar_acervo_icone = "$('#adicionar_acervo').hide();";
		} else {
			$apagar_acervo_icone = "$('#remover_acervo').hide();";
		}
		echo "
			<script type='text/javascript'>
				$apagar_acervo_icone
				acervo_item = $pagina_item_id;
				$('#adicionar_acervo').click(function() {
				    $.post('engine.php', {
			        'adicionar_item_acervo': acervo_item
				    }, function(data) {
				       if (data != 0) {
						    	$('#adicionar_acervo').hide();
						    	$('#remover_acervo').show();
				       	}
				    });
		    });
				$('#remover_acervo').click(function() {
				    $.post('engine.php', {
				        'remover_item_acervo': acervo_item
				    }, function(data) {
				        if (data != 0) {
							    $('#adicionar_acervo').show();
							    $('#remover_acervo').hide();
				        }
				    });
				});
			</script>
		";
	}
	if ($carregar_toggle_paginas_livres == true) {
		if ($area_interesse_ativa == true) {
			$apagar_area_interesse_icone = "$('#adicionar_area_interesse').hide();";
		} else {
			$apagar_area_interesse_icone = "$('#remover_area_interesse').hide();";
		}
		echo "
			<script type='text/javascript'>
			$apagar_area_interesse_icone
			area_interesse_item = $pagina_item_id;
			$('#adicionar_area_interesse').click(function() {
			    $.post('engine.php', {
			       'adicionar_area_interesse': area_interesse_item
			    }, function(data) {
			        if (data != 0) {
			            $('#adicionar_area_interesse').hide();
			            $('#remover_area_interesse').show();
			        }
			    });
			});
			$('#remover_area_interesse').click(function() {
			    $.post('engine.php', {
			        'remover_area_interesse': area_interesse_item
			    }, function(data) {
			        if (data != 0) {
			            $('#adicionar_area_interesse').show();
			            $('#remover_area_interesse').hide();
			        }
			    });
			});
			</script>
		";
	}
	if ($carregar_remover_usuarios == true) {
		echo "
			<script type='text/javascript'>
				$(document).on('click', '.remover_membro_grupo', function() {
					var membro_remover = $(this).attr('value');
					var grupo_remover = $('#remover_membro_grupo_id').val();
					$.post('engine.php', {
					   'remover_membro_grupo_id': grupo_remover,
					   'remover_membro_user_id': membro_remover
					}, function(data) {
					    if (data != 0) {
					        alert('Membro removido.');
					        $(this).hide();
					    } else {
					        alert('Ocorreu algum problema');
					    }
					});
				});
			</script>
		";
	}
	if ($carregar_notificacoes == true) {
		echo "
			<script type='text/javascript'>
			$(document).ready(function() {
			";
		if ($notificacao_ativa == true) {
			echo "$('#artefato_notificar_inativo').addClass('d-none');";
		} else {
			echo "$('#artefato_notificar_ativo').addClass('d-none');";
		}
		if ($notificacao_email == true) {
			echo "$('#artefato_nao_notificar_email').addClass('d-none');";
		} else {
			echo "$('#artefato_notificar_email').addClass('d-none');";
		}
		echo "
				});
				$(document).on('click', '#trigger_notificar_ativo', function() {
				    $('#notificacao_ativa').val(0);
				    $('#notificacao_email').val(0);
				    $('#artefato_notificar_inativo').removeClass('d-none');
				    $('#artefato_notificar_ativo').addClass('d-none');
				    $('#artefato_notificar_email').addClass('d-none');
				    $('#artefato_nao_notificar_email').removeClass('d-none');
				})
				$(document).on('click', '#trigger_notificar_inativo', function() {
				    $('#notificacao_ativa').val(1);
				    $('#notificacao_email').val(0);
				    $('#artefato_notificar_inativo').addClass('d-none');
				    $('#artefato_notificar_ativo').removeClass('d-none');
				    $('#artefato_notificar_email').addClass('d-none');
				    $('#artefato_nao_notificar_email').removeClass('d-none');
				})
				$(document).on('click', '#trigger_notificar_email', function() {
				    $('#notificacao_email').val(0);
				    $('#artefato_notificar_email').addClass('d-none');
				    $('#artefato_nao_notificar_email').removeClass('d-none');
				})
				$(document).on('click', '#trigger_nao_notificar_email', function() {
				    $('#notificacao_ativa').val(1);
				    $('#notificacao_email').val(1);
				    $('#artefato_notificar_inativo').addClass('d-none');
				    $('#artefato_notificar_ativo').removeClass('d-none');
				    $('#artefato_notificar_email').removeClass('d-none');
				    $('#artefato_nao_notificar_email').addClass('d-none');
				})
				
			</script>
		";
	}
	if ($carregar_controle_estado == true) {
		echo "
			<script type='text/javascript'>
				$(document).on('click', '#trigger_estado_rascunho', function() {
				    $.post('engine.php', {
				    	'novo_estado_pagina': 1,
				    	'novo_estado_pagina_id': {$pagina_id}
				    });
				    window.location.reload(true);
				});
				$(document).on('click', '#trigger_estado_aceitavel', function() {
				    $.post('engine.php', {
				    	'novo_estado_pagina': 2,
				    	'novo_estado_pagina_id': {$pagina_id}
				    });
				    window.location.reload(true);
				});
				$(document).on('click', '#trigger_estado_desejavel', function() {
				    $.post('engine.php', {
				    	'novo_estado_pagina': 3,
				    	'novo_estado_pagina_id': {$pagina_id}
				    });
				    window.location.reload(true);
				});
				$(document).on('click', '#trigger_estado_excepcional', function() {
				    $.post('engine.php', {
				    	'novo_estado_pagina': 4,
				    	'novo_estado_pagina_id': {$pagina_id}
				    });
				    window.location.reload(true);
				});
			</script>
		";
	}
	if ($carregar_modal_login == true) {
		if (!isset($thinkific_email)) {
			echo "<script type='text/javascript'>
				function isEmail(email) {
                  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                  return regex.test(email);
                }
				$('#secao_login_email').show();
				$('#login_mensagem_basica').show();
				$('#login_email').keyup(function() {
				    email = $('#login_email').val();
				    email_check = isEmail(email);
				    if (email_check == true) {
				        $('#secao_login_senha').show();
				    } else {
				        $('#secao_login_senha').hide();
				    }
				});
				$('#login_senha').keyup(function() {
                    var senha = $('#login_senha').val();
                    var senha_length = senha.length;
                    if (senha_length > 5) {
                        $('#botao_login').prop('disabled', false)
                    } else {
                        $('#botao_login').prop('disabled', true)
                    }
                 });
				$('#login_senha_confirmacao').keyup(function() {
                    var senha = $('#login_senha').val();
   				    var senha2 = $('#login_senha_confirmacao').val();
                    if (senha == senha2) {
                        $('#botao_login').prop('disabled', false)
                    } else {
                        $('#botao_login').prop('disabled', true)
                    }
                 });
				
                  $(document).on('click', '#botao_login', function() {
					  $(this).prop('disabled', true)
                      var login = $('#login_email').val();
                      var senha = $('#login_senha').val();
                      var senha2 = $('#login_senha_confirmacao').val();
                      if (senha2 == false) {
                        $.post('engine.php', {
                            'login_email': login,
                            'login_senha': senha,
                            'login_origem': 'desconhecido'
                        }, function(data) {
                            if ((data == 1) || (data == 11)) {
                                pagina_id = '$pagina_id';
                                if (pagina_id != false){
                                	window.location.replace('pagina.php?pagina_id=$pagina_id');
                                } else {
                                    window.location.reload(true);
                                }
                            } else if (data == 0) {
                                $('#login_mensagem_basica').addClass('text-muted');
                                $('#login_senha_incorreta').show();
                            } else if (data == 'novo_usuario') {
                                $('#login_mensagem_basica').addClass('text-muted');
                                $('#login_novo_usuario').show();
								$('#login_senha_confirmacao').prop('disabled', false);
								$('#secao_login_confirmacao').show();
								$('#login_senha_confirmacao').focus();
                                $('#login_senha_incorreta').hide();
                                $('#login_email').prop('disabled', true);
                                $('#login_senha').prop('disabled', true);
                                $('#botao_login').prop('disabled', true);
                            } else if (data == 'thinkific') {
                                $('#login_thinkific_registro').show();
                                $('#login_mensagem_basica').addClass('text-muted');
                                $('#login_email').prop('disabled', true);
                                $('#login_senha').prop('disabled', true);
                            } else if (data == 'confirmacao') {
                                $('#login_senha_confirmar').show();
                                $('#login_senha_incorreta').hide();
                            }
                        });
                      } else {
                          if (senha != senha2) {
                              alert('Senhas não conferem.');
                          } else {
                              $.post('engine.php', {
                                 'login_email': login,
                                 'login_senha': senha,
                                 'login_senha2': senha2,
                              }, function(data) {
                                  if (data == 1) {
                                      window.location.reload(true);
                                  } else {
                                      alert('{$pagina_translated['Ocorreu algum problema, sua conta não foi criada']}.');
                                  }
                              });
                          }
                      }
                  });
			</script>
		";
		} else {
			$query = prepare_query("SELECT id FROM usuarios WHERE email = '$thinkific_email' AND senha IS NULL");
			$usuarios = $conn->query($query);
			if ($usuarios->num_rows == 0) {
				echo "
	            <script type='text/javascript'>
	               $('#thinkific_senha_existe').show();
	               $('#login_thinkific_email').val('$thinkific_email');
	               $('#secao_login_thinkific_email').show();
	               $('#secao_login_senha').show();
	               $('#secao_login_confirmacao').hide();
	               $('#login_senha').keyup(function() {
	                    var senha = $('#login_senha').val();
	                    var senha_length = senha.length;
	                    if (senha_length > 5) {
	                        $('#botao_login').prop('disabled', false)
	                    } else {
	                        $('#botao_login').prop('disabled', true)
	                    }
	               });
	               $(document).on('click', '#botao_login', function() {
	                    var login = '$thinkific_email';
	                    var senha = $('#login_senha').val();
	                    $.post('engine.php', {
	                        'login_email': login,
	                        'login_senha': senha,
	                        'login_origem': 'thinkific'
	                    }, function(data) {
	                        if (data != 0) {
	                            window.location.reload(true);
	                        } else {
	                            $('#thinkific_senha_existe').addClass('text-muted');
	                            $('#thinkific_senha_incorreta').show();
	                        }
	                    });
	               });
                </script>
	        ";
			} else {
				echo "
                <script type='text/javascript'>
                    $('#secao_login_thinkific_email').show();
                    $('#thinkific_transfer').show();
                    $('#login_thinkific_email').val('$thinkific_email');
                    $('#secao_login_senha').show();
                    $('#secao_login_confirmacao').show();
                    $('#login_senha_confirmacao').prop('disabled', false);
                    $('#login_senha_confirmacao').keyup(function() {
                       var senha1 = $('#login_senha').val();
                       var senha2 = $('#login_senha_confirmacao').val();
                       var senha_length = senha2.length;
                       if (senha_length > 5) {
                           if (senha2 != '') {
                               if (senha1 == senha2) {
                                   $('#botao_login').prop('disabled', false)
                               }
                           } else {
                               $('#botao_login').prop('disabled', true)
                           }
                       } else {
                          $('#botao_login').prop('disabled', true)
                       }
                       if (senha1 != senha2) {
                          $('#botao_login').prop('disabled', true)
                       }
                    });
                    $(document).on('click', '#botao_login', function() {
                       var login = '$thinkific_email';
                       var senha1 = $('#login_senha').val();
                        $.post('engine.php', {
                            'thinkific_login': login,
                            'thinkific_senha': senha1,
                        }, function(data) {
                            if (data != 0) {
                                window.location.reload(true);
                            } else {
                                alert('Senha incorreta.');
                            }
                        });
                    });
                </script>
            ";
			}
		}
	}
	if (($pagina_tipo == 'ubwiki') || ($pagina_tipo == 'login')) {
		echo "
		<script type=\"text/javascript\">
		
        $('.icone_sobre_stuff').removeClass('d-none');
        $('.icones_links').addClass('d-none');
        $('#artefato_escritorio_link').removeClass('d-none');
        $('.sobre_stuff').addClass('d-none');
        $('#sobre_escritorio').removeClass('d-none');
        $('#artefato_escritorio').addClass('d-none');
        
        $('#artefato_escritorio').click(function () {
            $('.icone_sobre_stuff').removeClass('d-none');
            $('.icones_links').addClass('d-none');
            $('#artefato_escritorio_link').removeClass('d-none');
            $('.sobre_stuff').addClass('d-none');
            $('#sobre_escritorio').removeClass('d-none');
            $('#artefato_escritorio').addClass('d-none');
        });
        $('#artefato_cursos').click(function () {
            $('.icone_sobre_stuff').removeClass('d-none');
            $('.icones_links').addClass('d-none');
            $('#artefato_cursos_link').removeClass('d-none');
            $('.sobre_stuff').addClass('d-none');
            $('#sobre_cursos').removeClass('d-none');
            $('#artefato_cursos').addClass('d-none');
        });
        $('#artefato_areas_interesse').click(function () {
            $('.icone_sobre_stuff').removeClass('d-none');
            $('.icones_links').addClass('d-none');
            $('#artefato_areas_interesse_link').removeClass('d-none');
            $('.sobre_stuff').addClass('d-none');
            $('#sobre_areas_interesse').removeClass('d-none');
            $('#artefato_areas_interesse').addClass('d-none');
        });
        $('#artefato_biblioteca').click(function () {
            $('.icone_sobre_stuff').removeClass('d-none');
            $('.icones_links').addClass('d-none');
            $('#artefato_biblioteca_link').removeClass('d-none');
            $('.sobre_stuff').addClass('d-none');
            $('#sobre_biblioteca').removeClass('d-none');
            $('#artefato_biblioteca').addClass('d-none');
        });
        $('#artefato_forum').click(function () {
            $('.icone_sobre_stuff').removeClass('d-none');
            $('.icones_links').addClass('d-none');
            $('#artefato_forum_link').removeClass('d-none');
            $('.sobre_stuff').addClass('d-none');
            $('#sobre_forum').removeClass('d-none');
            $('#artefato_forum').addClass('d-none');
        });
        $('#artefato_loja').click(function () {
            $('.icone_sobre_stuff').removeClass('d-none');
            $('.icones_links').addClass('d-none');
            $('#artefato_loja_link').removeClass('d-none');
            $('.sobre_stuff').addClass('d-none');
            $('#sobre_loja').removeClass('d-none');
            $('#artefato_loja').addClass('d-none');
        });

    </script>
		";
	}

	if ($quill_was_loaded == true) {
		echo "
			<script type='text/javascript'>
				$('#quill_editor_anotacoes').keydown(function (e) {
				    if (e.ctrlKey && e.keyCode == 13) {
				        $('#anotacoes_trigger_save').click();
  					}
				    if (e.ctrlKey && e.keyCode == 83) {
				        event.preventDefault();
				        $('#anotacoes_trigger_save').click();
  					}
				    if (e.metaKey && e.keyCode == 13) {
				        $('#anotacoes_trigger_save').click();
  					}
				    if (e.metaKey && e.keyCode == 83) {
				        event.preventDefault();
				        $('#anotacoes_trigger_save').click();
  					}
				   
				});
				$('#quill_editor_verbete').keydown(function (e) {
				    if (e.ctrlKey && e.keyCode == 13) {
				        $('#verbete_trigger_save').click();
  					}
				    if (e.ctrlKey && e.keyCode == 83) {
				        event.preventDefault();
				        $('#verbete_trigger_save').click();
  					}
				    if (e.metaKey && e.keyCode == 13) {
				        $('#verbete_trigger_save').click();
  					}
				    if (e.metaKey && e.keyCode == 83) {
				        event.preventDefault();
				        $('#verbete_trigger_save').click();
  					}
				});
				function into_sepia() {
					$('.swatch_button').attr('value', 'dark');
					
					$('.ql-editor').addClass('bg-sepia p-1 text-dark-sepia rounded font-serif');
					$('.swatch_button').removeClass('text-dark-sepia bg-sepia bg-dark link-warning link-dark bg-white');
					$('.swatch_button').addClass('bg-sepia text-dark-sepia');
				}
				function into_dark() {
					$('.swatch_button').attr('value', 'default');
					
					$('.ql-editor').removeClass('bg-sepia text-dark-sepia font-serif');
					$('.ql-editor').addClass('bg-dark link-warning font-monospace p-1 rounded');
					$('.swatch_button').removeClass('link-dark bg-white');
					$('.swatch_button').addClass('bg-dark link-warning');
				}
				function into_default() {
					$('.swatch_button').attr('value', 'sepia');
					
					$('.ql-editor').removeClass('p-1 bg-dark link-warning font-monospace');
					$('.swatch_button').removeClass('text-dark-sepia bg-sepia bg-dark link-warning link-dark bg-white');
					$('.swatch_button').addClass('bg-white link-dark');
				}
				$(document).on('click', '.swatch_button', function() {
				    current_color = $(this).attr('value');
					$.post('engine.php', {
				        'change_color': current_color
				    })
				    if (current_color == 'sepia') {
				        into_sepia();
				    } else if (current_color == 'dark') {
				        into_dark();
				    } else if (current_color == 'default') {
						into_default();
				    }
				});
				$('.zoom_out').hide();
				$(document).on('click', '.zoom_out', function() {
				    $('.zoom_out').hide();
				    $('.zoom_in').show();
				    $('.ql-editor').css('zoom','100%');
				})
				$(document).on('click', '.zoom_in', function() {
				    $('.zoom_in').hide();
				    $('.zoom_out').show();
				    $('.ql-editor').css('zoom','110%');
				});
		";
		switch ($user_quill_colors) {
			case 'sepia':
				echo "into_sepia();";
				break;
			case 'dark':
				echo "into_dark();";
				break;
			default:
				echo "into_default();";
		}
		echo "
			</script>
		";
	}


	if ($pagina_tipo == 'curso') {
		echo "
			<script type='text/javascript'>
				$(document).on('click', '#reveal_introduction', function() {
				    $(this).addClass('d-none');
				    $('#verbete').removeClass('d-none');
				});
			</script>
		";
	}

//navbar titlebar functionsbar
	if ($pagina_padrao == true) {
		if (!isset($opcao_hide_navbar)) {
			$opcao_hide_navbar = false;
		}
		if ($opcao_hide_navbar == true) {
			$hide_bars = "$('#hide_bars').click();";
		} else {
			$hide_bars = false;
		}
		echo "
        <script type='text/javascript'>
            $(document).on('click', '#hide_bars', function() {
                $(this).addClass('d-none');
                $('#show_bars').removeClass('d-none');
                $('#navbar').addClass('d-none');
                $('#titlebar').addClass('d-none');
            });
            $(document).on('click', '#show_bars', function() {
        $(this).addClass('d-none');
        $('#hide_bars').removeClass('d-none');
                $('#navbar').removeClass('d-none');
                $('#titlebar').removeClass('d-none');
            });
            $hide_bars
        </script>
    ";
	}
	if ($carregar_toggle_curso == true) {
		echo "
        <script type='text/javascript'>
            $(document).on('click', '#curso_aderir', function() {
                $.post('engine.php', {
                    'curso_aderir': $pagina_curso_id
                }, function(data) {
                    if (data != 0) {
						$('#curso_aderir').addClass('d-none');
						$('#curso_sair').removeClass('d-none');
                    }
                })
            });
            $(document).on('click', '#curso_sair', function() {
                $.post('engine.php', {
                    'curso_sair': $pagina_curso_id
                }, function (data) {
                    if (data != 0) {
						$('#curso_sair').addClass('d-none');
						$('#curso_aderir').removeClass('d-none');
                    }
                })
            });
        </script>
    ";
	}

	if ($pagina_tipo == 'escritorio') {
		echo "
        <script type='text/javascript'>
            $(document).on('click', '#mostrar_formulario_codigo', function() {
               $(this).addClass('d-none');
               $('#formulario_codigo').removeClass('d-none');
            });
		";
		if (isset($_GET['wllt'])) {
			echo "
				$('#trigger_wallet').click();
			";
		}
		echo "</script>";
	}
	if ($loaded_correcao_form == true) {
		echo "
        <script type='text/javascript'>
            var disable_submit = function() {
                $('#trigger_review_recalc').removeClass('d-none');
                $('#trigger_review_send').addClass('d-none');
                $('#trigger_review_send').prop('disabled', true);
            }
            var recalc_review = function() {
                if ($('#review_grade').is(':checked')) {
                    review_grade = 'with_grade';
                } else {
                    review_grade = 'no_grade';
                }
                if ($('#revisao_diplomata').is(':checked')) {
                    revisao_diplomata = 'revisao_diplomata';
                } else {
                    revisao_diplomata = 'nao_diplomata';
                }
                reviewer_choice = $('input[name=reviewer_choice]:checked', '#review_form').val();
                extension = $('input[name=extension]:checked', '#review_form').val();
                reviewer_chat = $('input[name=reviewer_chat]:checked', '#review_form').val();
                $.post('engine.php', {
                    'recalc_review_pagina_id': $pagina_id,
                    'recalc_reviewer_choice': reviewer_choice,
                    'recalc_extension': extension,
                    'recalc_review_grade': review_grade,
                    'recalc_reviewer_chat': reviewer_chat,
                    'recalc_revisao_diplomata': revisao_diplomata
                }, function (data) {
                    if (data != 0) {
                        var results = JSON.parse(data);
                        var new_price = results[0];
                        var new_wordcount = results[1];
                        $('#review_price').empty();
                        $('#review_price').append(new_price);
                        $('#review_wordcount').empty();
                        $('#review_wordcount').append(new_wordcount);
                    }
                })
            }
            $(document).on('click', '#carregar_modal_correcao', function() {
                recalc_review();
                disable_submit();
            })
            $(document).on('click', '.disable_submit', function() {
                recalc_review();
                disable_submit();
            })
            $(document).on('click', '#trigger_review_recalc', function() {
                recalc_review();
                $(this).addClass('d-none');
                $('#trigger_review_send').removeClass('d-none');
                $('#trigger_review_send').prop('disabled', false);
            })
        </script>
    ";
	}

	if ($load_change_into_model == true) {
		echo "
		<script type='text/javascript'>
			$(document).on('click', '#change_into_model', function() {
			    $.post('engine.php', {
			        'change_into_model_pagina_id': $pagina_id,
			    }, function(data) {
			        if (data != 0) {
						window.location.reload(true);
			        } else {
			            alert('Some problem happened');
			        }
			    });
			});
		</script>
	";
	}

	if ($open_review_modal == true) {
		echo "
			<script type='text/javascript'>
				$('#carregar_modal_correcao').click();
			</script>
		";
	}

	if ($carregar_publicar_modelo == true) {
		echo "
			<script type='text/javascript'>
				$(document).on('click', '#publicar_modelo', function() {
				    $.post('engine.php', {
				        'publicar_modelo': $pagina_id,
				    }, function (data) {
				        if (data != 0) {
				            window.location.reload(true);
				        } else {
				            alert('Some problem happened');
				        }
				    });
				});
			</script>
		";
	}

	if ($pagina_tipo == 'bfranklin') {
		echo "
			<script type='text/javascript'>
				$(document).on('click', '.criar_novo_modelo', function() {
					$.post('engine.php', {
						'criar_novo_modelo': true
					}, function (data) {
						if (data != 0) {
						    window.location.reload(true);
						} else {
							alert('Some problem happened')
						}
					});
				});
			</script>
		";
	}

	if ($pagina_subtipo == 'modelo') {
		echo "
			<script type='text/javascript'>
				$(document).on('click', '.escritorio_modelo', function() {
					var this_operation = $(this).attr('value');
					$.post('engine.php', {	
					    'escritorio_modelo_operation': this_operation,
						'escritorio_modelo_pagina_id': $pagina_id
					}, function (data) {
						if (data != 0) {
						    if (this_operation == 'adicionar_modelo') {
							    $('#adicionar_modelo').addClass('d-none');
							    $('#remover_modelo').removeClass('d-none');
							    window.location.reload(true);
							} else {
						        $('#adicionar_modelo').removeClass('d-none');
						        $('#remover_modelo').addClass('d-none');
						        window.location.reload(true);
							}
						} else {
							alert('Some problem happened');
						}
					})
				})
				$(document).on('click', '#adicionar_escritorio_modelo', function() {
				    $.post('engine.php', {
				        'escritorio_modelo_operation': 'adicionar_modelo',
				        'escritorio_modelo_pagina_id': $pagina_id
				    }, function (data) {
				        if (data != 0) {
				            $('#adicionar_escritorio_modelo').addClass('d-none');
							$('#adicionar_modelo').addClass('d-none');
							$('#remover_modelo').removeClass('d-none');
							$('.modelo_esconder_paragrafo').removeClass('d-none');
							window.location.reload(true);
				        } else {
				            alert('Some problem happened')
				        }
				    })
				})
				$(document).on('click', '.modelo_esconder_paragrafo', function() {
					$(this).addClass('d-none')
				    $.post('engine.php', {
				        'modelo_esconder_paragrafo': $pagina_id
				    }, function (data) {
				        if (data != 0) {
				            $('#modelo').addClass('d-none');
				        } else {
				            alert('Some problem has happened')
				        }
				    })
				})
				$(document).on('click', '.modelo_mostrar_paragrafo', function() {
					$(this).addClass('d-none')
				    $.post('engine.php', {
				        'modelo_mostrar_paragrafo': $pagina_id
				    }, function (data) {
				        if (data != 0) {
							$('#modelo').removeClass('d-none');
				        } else {
				            alert('Some problem has happened')
				        }
				    })
				})
			</script>
		";
	}

	if ($pagina_subtipo == 'etiqueta') {
		if ($user_tipo == 'admin') {
			echo "
				<script type='text/javascript'>
					$(document).on('click', '#apagar_etiqueta', function() {
					    var verify = confirm('Quer mesmo apagar essa etiqueta completamente?');
					    if (verify) {
							$.post('engine.php', {
								'apagar_etiqueta_pagina_id': $pagina_id,
							}, function (data) {
								if (data != 0) {
									window.location.reload(true);
								} else {
									alert('Some problem has happened');
								}
							})
					    }
					})
				</script>
			";
		}
	}

	if ($pagina_compartilhamento_por_link == true) {
		echo "
			<script type='text/javascript'>
				$(document).on('click', '#permitir_acesso_por_link', function() {
				    $.post('engine.php', {
				        'permitir_acesso_por_link': $pagina_id,
				    }, function (data) {
				        if (data != 0) {
				            window.location.reload(true);
				        } else {
				            alert('Some problem happened');
				        }
				    })
				})
			</script>
		";
	}

	if ($pagina_subtipo == 'plano') {
		echo "
			<script type='text/javascript'>
				$(document).on('click', '.set_state_entrada_value', function() {
					var this_entrada_id = $(this).attr('value');
					$('#set_state_entrada_id').val(this_entrada_id);
				});
				$(document).on('click', '.set_this_state', function() {
				    var this_entrada_id = $('#set_state_entrada_id').attr('value');
				    var this_entrada_novo_estado = $(this).attr('value');
				    $.post('engine.php', {
				        'novo_estado_plano_id': $pagina_item_id,
				        'novo_estado_entrada_id': this_entrada_id,
				        'novo_estado_codigo': this_entrada_novo_estado
				    }, function (data) {
				        if (data != 0) {
				            window.location.reload(true);
				        }
				    })
				})
				$(document).on('click', '.set_comment_elemento_id', function() {
				    var this_elemento_id = $(this).attr('value');
				    $('#set_comment_elemento_id').val(this_elemento_id);
				})
				$(document).on('click', '.set_tag_elemento_value', function() {
				    var this_elemento_id = $(this).attr('value');
				    $('#set_tag_elemento_id').val(this_elemento_id);
				})
			</script>
		";
	}

	if ($trigger_criar_simulado == true) {
		echo "
		<script type='text/javascript'>
			$(document).on('click', '#trigger_criar_simulado', function() {
			    $.post('engine.php', {
			        'criar_simulado_pagina_id': $pagina_id
			    }, function (data) {
			        if (data != 0) {
			            window.location.reload(true);
			        }
			    })
			})
		</script>";
	}

	if ($pagina_tipo == 'anotacoes') {
		echo "
			<script type='text/javascript'>
				$(document).on('click', '.upvote', function() {
				    user_upvoted = $(this).attr('value');
				    $.post('engine.php', {
				        'usuario_upvote_pagina_id': $pagina_id,
				        'usuario_upvote_anotacao_id': user_upvoted
				    }, function (data) {
				        if (data != 0) {
				            window.location.reload(true);
				        }
				    })
				})
			</script>
		";
	}

	if ($modal_pagina_dados == true) {
		echo "
		<script type='text/javascript'>
			$(document).on('click', '#pagina_elementos_remover', function() {
			    $.post('engine.php', {
			        'listar_elementos_pagina_id': $pagina_id
			    }, function(data) {
			        if (data != 0) {
			            $('#body_modal_remover_elementos').empty();
			            $('#body_modal_remover_elementos').append(data);
			        }
			    })
			})
			$(document).on('click', '.remover_elemento_item', function() {
			    desabilitar_elemento_id = $(this).attr('value');
			    $.post('engine.php', {
			        'desabilitar_elemento_id': desabilitar_elemento_id
			    }, function(data) {
			        if (data != 0) {
			            window.location.reload(true);
			        } else {
			            alert('Some problem happened');
			        }
			    })
			})
			$(document).on('click', '.reativar_elemento_item', function() {
			    reativar_elemento_id = $(this).attr('value');
			    $.post('engine.php', {
			        'reativar_elemento_id': reativar_elemento_id
			    }, function(data) {
			        if (data != 0) {
			            window.location.reload(true);
			        } else {
			            alert('Some problem happened');
			        }
			    })
			})
		</script>
		";
	}
	if ($pagina_tipo == 'texto') {
		echo "
			<script type='text/javascript'>
				$('#coluna_esquerda').removeClass('col-lg-6');
				$('#coluna_esquerda').addClass('col-lg-8');
			</script>
		";
	}
	if ($anotacoes_existem == true) {
		$anotacoes_startup = "
			colunas('duas');
		";
	} else {
		$anotacoes_startup = "
			colunas('esquerda');
		";
	}
	if ($anotacoes_col == true) {
		echo "
			<script type='text/javascript'>
				
				var colunas = function(mode) {
				    if (mode == 'duas') {
						$('#coluna_esquerda').addClass('col-lg-6');
						$('#coluna_esquerda').removeClass('col-lg-8');
						$('#coluna_direita').addClass('col-lg-6');
						$('#coluna_direita').removeClass('col-lg-8');
						$('#coluna_direita').show();
						$('#coluna_esquerda').show();
						$('#esconder_coluna_direita').show();
						$('#mostrar_coluna_direita').hide();
						$('#mostrar_coluna_esquerda').hide();
						$('#esconder_coluna_esquerda').show();
						$('#esconder_coluna_esquerda_inner').show();
				    } else if (mode == 'esquerda') {
						$('#coluna_direita').hide();
						$('#coluna_esquerda').show();
						$('#coluna_esquerda').removeClass('col-lg-6');
						$('#coluna_esquerda').addClass('col-lg-8');
						$('#coluna_direita').addClass('col-lg-6');
						$('#coluna_direita').removeClass('col-lg-8');
						$('#mostrar_coluna_direita').show();
						$('#esconder_coluna_esquerda').show();
						$('#mostrar_coluna_esquerda').hide();
						$('#esconder_coluna_esquerda_inner').show();
				    } else if (mode == 'direita') {
						$('#coluna_esquerda').hide();
						$('#coluna_direita').show();
						$('#coluna_direita').removeClass('col-lg-6');
						$('#coluna_direita').addClass('col-lg-8');
						$('#mostrar_coluna_esquerda').show();
						$('#esconder_coluna_esquerda').hide();
						$('#mostrar_coluna_direita').hide();
				    }
				}
				
				$anotacoes_startup
				
				$(document).on('click', '#mostrar_coluna_direita', function() {
				    colunas('duas');
				})
				$('#anotacoes').on('click', '#esconder_coluna_direita', function() {
				    colunas('esquerda');
				})
				$('#anotacoes').on('click', '#mostrar_coluna_esquerda', function() {
				    colunas('duas');
				})
				$('#anotacoes').on('click', '#esconder_coluna_esquerda', function() {
				    colunas('direita');
				})
				$('#verbete').on('click', '#esconder_coluna_esquerda_inner', function() {
				    colunas('direita');
				})
			</script>
		";
	} else {
		echo "
			<script type='text/javascript'>
				$('#esconder_coluna_esquerda_inner').remove();
			</script>
		";
	}

	if ($carregar_secoes == true) {
		echo "<script type='text/javascript'>
			$(document).on('click', '#mostrar_instrucoes_secoes', function() {
			    $('.instrucoes_secoes').removeClass('d-none');
			    $(this).addClass('d-none');
			})
			$(document).on('click', '#adicionar_varias_secoes', function() {
			    $('.instrucoes_secoes').addClass('d-none');
			    $(this).addClass('d-none');
			    $('#mostrar_instrucoes_secoes').addClass('d-none');
			    $('#adicionar_multiplas_secoes_textarea').removeClass('d-none');
			    $('.instrucoes_multiplas_secoes').removeClass('d-none');
			    $('.adicionar_uma_secao').empty();
			})
		</script>";
	}

	//This is to make sure that when a modal opens, all others close.
	echo "
	<script class='text/javascript'>
		$('body').on('show.bs.modal', '.modal', function () {
			$('.modal:visible').removeClass('fade').modal('hide').addClass('fade');
		});
	</script>
	";

	echo "
	<script class='text/javascript'>
	web3.eth.getAccounts().then(function(accounts) {
	  var address = accounts[0];
	});
    
    var xhr = new XMLHttpRequest();
	xhr.open('POST', '/login');
	xhr.setRequestHeader('Content-Type', 'application/json');
	xhr.onreadystatechange = function() {
	  if (xhr.readyState === XMLHttpRequest.DONE) {
		if (xhr.status === 200) {
		  // handle the case where the user is authenticated
		} else {
		  // handle the case where the user is not authenticated
		}
	  }
	};
	xhr.send(JSON.stringify({address: address}));

	</script>
	";

//	echo "
//	<script type='text/javascript'>
//		var modal_login = new bootstrap.Modal(document.getElementById('modal_login'), {
//			keyboard: false
//		});
//		modal_login.show();
//	</script>
//	";