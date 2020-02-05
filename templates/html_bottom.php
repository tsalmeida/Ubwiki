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
	
	echo "
    <!-- Bootstrap tooltips -->
    <script type='text/javascript' src='js/popper.min.js'></script>
    <!-- Bootstrap core JavaScript -->
    <script type='text/javascript' src='js/bootstrap.min.js'></script>
    <!-- MDB core JavaScript -->
    <script type='text/javascript' src='js/mdb.min.js'></script>
  ";
	
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
	/*if ($texto_editar_titulo == true) {
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
	}*/
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
											alert('Elemento adicionado com sucesso');
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
					$('#trigger_buscar_referencias').click(function() {
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
				    $(this).hide();
				    $('#referencias_disponiveis').hide();
				    $('#criar_referencia_form').show();
				}),
				$(document).on('click', '.adicionar_autor', function() {
				    var adicionar_autor = $(this).attr('value');
				    $(this).hide();
				    $('#criar_referencia_autor').val(adicionar_autor);
				    $('#autores_disponiveis').hide();
				}),
				$(document).on('click', '#trigger_adicionar_referencia', function() {
				    var adicionar_referencia_titulo = $('#criar_referencia_titulo').val();
				    var adicionar_referencia_autor = $('#criar_referencia_autor').val();
				    var adicionar_referencia_tipo = $('#criar_referencia_tipo').val();
				    if ((adicionar_referencia_titulo != false) && (adicionar_referencia_autor != false) && (adicionar_referencia_tipo != false)) {
				        $.post('engine.php', {
				           'adicionar_referencia_titulo': adicionar_referencia_titulo,
				           'adicionar_referencia_autor': adicionar_referencia_autor,
				           'adicionar_referencia_tipo': adicionar_referencia_tipo,
				           'adicionar_referencia_contexto': '$pagina_tipo',
				           'adicionar_referencia_pagina_id': $pagina_id
				        }, function(data) {
				            if (data != false) {
				                alert('Referência criada e adicionada.')
				            }
				        });
				    } else {
				        alert('Por favor, preencha todos os campos necessários.')
				    }
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
			      $(this).hide();
			      $.post('engine.php', {
			         'curso_nova_materia_id': this_id,
			         'curso_nova_materia_pagina_id': $pagina_id,
			         'curso_nova_materia_user_id': $user_id
			      }, function(data) {
			         if (data != 0) {
	                 alert('Matéria adicionada, recarregue a página para atualizar');
			         }
			      });
			  });
				$(document).on('click', '#criar_materia', function() {
		      var new_tag = $(this).attr('value');
		      $(this).hide();
		      $.post('engine.php', {
		         'criar_materia_titulo': new_tag,
		         'criar_materia_page_id': {$pagina_id},
		         'criar_materia_page_tipo': '{$pagina_tipo}'
		      }, function(data) {
		         if (data != 0) {
		             alert('Matéria adicionada, recarregue a página para atualizar');
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
			      var this_id = $(this).attr('value');
			      $(this).hide();
			      $.post('engine.php', {
			         'curso_novo_topico_id': this_id,
			         'curso_novo_topico_pagina_id': $pagina_id,
			         'curso_novo_topico_user_id': $user_id
			      }, function(data) {
			         if (data != 0) {
	                 alert('Matéria adicionada, recarregue a página para atualizar');
			         }
			      });
			  });
				$(document).on('click', '#criar_topico', function() {
		      var new_tag = $(this).attr('value');
		      $(this).hide();
		      $.post('engine.php', {
		         'criar_topico_titulo': new_tag,
		         'criar_topico_page_id': {$pagina_id},
		         'criar_topico_page_tipo': '{$pagina_tipo}'
		      }, function(data) {
		         if (data != 0) {
		             alert('Matéria adicionada, recarregue a página para atualizar');
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
			      $(this).hide();
			      $.post('engine.php', {
			         'curso_novo_subtopico_id': this_id,
			         'curso_novo_subtopico_pagina_id': $pagina_id,
			         'curso_novo_subtopico_user_id': $user_id
			      }, function(data) {
			         if (data != 0) {
	                 alert('Matéria adicionada, recarregue a página para atualizar');
			         }
			      });
			  });
				$(document).on('click', '#criar_subtopico', function() {
		      var new_tag = $(this).attr('value');
		      $(this).hide();
		      $.post('engine.php', {
		         'criar_subtopico_titulo': new_tag,
		         'criar_subtopico_page_id': {$pagina_id},
		         'criar_subtopico_page_tipo': '{$pagina_tipo}'
		      }, function(data) {
		         if (data != 0) {
		             alert('Matéria adicionada, recarregue a página para atualizar');
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
	              $(this).hide();
			      $.post('engine.php', {
			         'remover_etiqueta_id': this_id,
			         'remover_etiqueta_page_id': {$pagina_id},
			         'remover_etiqueta_page_tipo': '{$pagina_tipo}'
			      });
			  });
			  $(document).on('click', '#criar_etiqueta', function() {
		      var new_tag = $(this).attr('value');
		      $(this).hide();
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
	/*
	if ($etiquetas_bottom_adicionar == true) {
		echo "
		<script type='text/javascript'>
			$('#buscar_etiquetas').keyup(function() {
        error_log('this happened');
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
		         'criar_etiqueta_page_id': {$pagina_id},
		         'criar_etiqueta_page_tipo': '{$pagina_tipo}'
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
			         'nova_etiqueta_page_id': {$pagina_id},
			         'nova_etiqueta_page_tipo': '{$pagina_tipo}'
			      }, function(data) {
			         if (data != 0) {
			             $('#etiquetas_ativas').append(data);
			         }
			      });
			  });
		</script>
	";
	}*/
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
					$('.ql-toolbar').addClass('sticky-top grey lighten-4 text-white border rounded border-0 mt-1');
		    });
			</script>
		";
	}
	if (isset($quill_extra_buttons)) {
		echo "
			<script type='text/javascript'>
			 $(document).ready(function() {
					var extra_buttons = \"$quill_extra_buttons\";
					$('.ql-toolbar').prepend(extra_buttons);
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
		            alert('O acesso do usuário a este documento foi revogado.');
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
		            alert('O acesso desse grupo de estudo a este documento foi revogado.');
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
	if ($pagina_tipo == 'forum-') {
		echo "
			<script type='text/javascript'>
				$('.stop').hide();
				$('.visualizer').hide();
				(function() {
				
					var promisifiedOldGUM = function(constraints, successCallback, errorCallback) {
				
						// First get ahold of getUserMedia, if present
						var getUserMedia = (navigator.getUserMedia ||
								navigator.webkitGetUserMedia ||
								navigator.mozGetUserMedia ||
								navigator.msGetUserMedia);
				
						// Some browsers just don't implement it - return a rejected promise with an error
						// to keep a consistent interface
						if(!getUserMedia) {
							return Promise.reject(new Error('getUserMedia is not implemented in this browser'));
						}
				
						// Otherwise, wrap the call to the old navigator.getUserMedia with a Promise
						return new Promise(function(successCallback, errorCallback) {
							getUserMedia.call(navigator, constraints, successCallback, errorCallback);
						});
						
					}
				
					// Older browsers might not implement mediaDevices at all, so we set an empty object first
					if(navigator.mediaDevices === undefined) {
						navigator.mediaDevices = {};
					}
				
					// Some browsers partially implement mediaDevices. We can't just assign an object
					// with getUserMedia as it would overwrite existing properties.
					// Here, we will just add the getUserMedia property if it's missing.
					if(navigator.mediaDevices.getUserMedia === undefined) {
						navigator.mediaDevices.getUserMedia = promisifiedOldGUM;
					}
					
				})();
				
				
				const record = document.querySelector('.record');
				const stop = document.querySelector('.stop');
				const soundClips = document.querySelector('.sound-clips');
				const canvas = document.querySelector('.visualizer');
				const mainSection = document.querySelector('.main-controls');
				
				// disable stop button while not recording
				
				stop.disabled = true;
				
				// visualiser setup - create web audio api context and canvas
				
				let audioCtx;
				const canvasCtx = canvas.getContext(\"2d\");
				
				//main block for doing the audio recording
				
				if (navigator.mediaDevices.getUserMedia) {
				  console.log('getUserMedia supported.');
				
				  const constraints = { audio: true };
				  let chunks = [];
				
				  let onSuccess = function(stream) {
				    const mediaRecorder = new MediaRecorder(stream);
				
				    visualize(stream);
				
				    record.onclick = function() {
							$('.stop').show();
							$('.visualizer').show();
							$('.record').hide();
				      mediaRecorder.start();
				      console.log(mediaRecorder.state);
				      console.log(\"recorder started\");
				      record.style.background = \"red\";
				
				      stop.disabled = false;
				      record.disabled = true;
				    }
				
				    stop.onclick = function() {
              $('.record').show();
							$('.stop').hide();
							$('.visualizer').hide();
				      mediaRecorder.stop();
				      console.log(mediaRecorder.state);
				      console.log(\"recorder stopped\");
				      record.style.background = \"\";
				      record.style.color = \"\";
				      // mediaRecorder.requestData();
				
				      stop.disabled = true;
				      record.disabled = false;
				    }
				
				    mediaRecorder.onstop = function(e) {
				      console.log(\"data available after MediaRecorder.stop() called.\");
				
				      const clipName = 'Mensagem de voz';
				      const clipContainer = document.createElement('article');
				      const clipLabel = document.createElement('p');
				      const audio = document.createElement('audio');
				      const deleteButton = document.createElement('button');
				
				      clipContainer.classList.add('clip');
				      audio.setAttribute('controls', '');
				      deleteButton.textContent = 'Delete';
				      deleteButton.className = 'delete';

			        clipLabel.textContent = clipName;
				
				      clipContainer.appendChild(audio);
				      clipContainer.appendChild(clipLabel);
				      clipContainer.appendChild(deleteButton);
				      soundClips.appendChild(clipContainer);
				
				      audio.controls = true;
				      const blob = new Blob(chunks, { 'type' : 'audio/ogg; codecs=opus' });
				      chunks = [];
				      const audioURL = window.URL.createObjectURL(blob);
				      audio.src = audioURL;
				      console.log(\"recorder stopped\");
				
				      deleteButton.onclick = function(e) {
				        evtTgt = e.target;
				        evtTgt.parentNode.parentNode.removeChild(evtTgt.parentNode);
				      }
				
				      clipLabel.onclick = function() {
				        const existingName = clipLabel.textContent;
				        const newClipName = prompt('Enter a new name for your sound clip?');
				        if(newClipName === null) {
				          clipLabel.textContent = existingName;
				        } else {
				          clipLabel.textContent = newClipName;
				        }
				      }
				    }
				
				    mediaRecorder.ondataavailable = function(e) {
				      chunks.push(e.data);
				    }
				  }
				
				  let onError = function(err) {
				    console.log('The following error occured: ' + err);
				  }
				
				  navigator.mediaDevices.getUserMedia(constraints).then(onSuccess, onError);
				
				} else {
				   console.log('getUserMedia not supported on your browser!');
				}
				
				function visualize(stream) {
				  if(!audioCtx) {
				    audioCtx = new AudioContext();
				  }
				
				  const source = audioCtx.createMediaStreamSource(stream);
				
				  const analyser = audioCtx.createAnalyser();
				  analyser.fftSize = 2048;
				  const bufferLength = analyser.frequencyBinCount;
				  const dataArray = new Uint8Array(bufferLength);
				
				  source.connect(analyser);
				  //analyser.connect(audioCtx.destination);
				
				  draw()
				
				  function draw() {
				    WIDTH = canvas.width
				    HEIGHT = canvas.height;
				
				    requestAnimationFrame(draw);
				
				    analyser.getByteTimeDomainData(dataArray);
				
				    canvasCtx.fillStyle = 'rgb(200, 200, 200)';
				    canvasCtx.fillRect(0, 0, WIDTH, HEIGHT);
				
				    canvasCtx.lineWidth = 2;
				    canvasCtx.strokeStyle = 'rgb(0, 0, 0)';
				
				    canvasCtx.beginPath();
				
				    let sliceWidth = WIDTH * 1.0 / bufferLength;
				    let x = 0;
				
				
				    for(let i = 0; i < bufferLength; i++) {
				
				      let v = dataArray[i] / 128.0;
				      let y = v * HEIGHT/2;
				
				      if(i === 0) {
				        canvasCtx.moveTo(x, y);
				      } else {
				        canvasCtx.lineTo(x, y);
				      }
				
				      x += sliceWidth;
				    }
				
				    canvasCtx.lineTo(canvas.width, canvas.height/2);
				    canvasCtx.stroke();
				
				  }
				}
				
				window.onresize = function() {
				  canvas.width = mainSection.offsetWidth;
				}
				
				window.onresize();
				
				
				
			</script>
		";
	}
?>