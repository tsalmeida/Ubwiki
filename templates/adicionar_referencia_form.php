<?php
	return "
		<div class='md-form'>
	    <input type='text' class='form-control' name='busca_referencias' id='busca_referencias' required>
	    <label for='busca_referencias'>$adicionar_referencia_busca_texto</label>
      <button type='button' class='$button_classes' id='trigger_buscar_referencias'>Buscar</button>
    </div>
    <div class='row' id='referencias_disponiveis'>
    </div>
    <div class='row' id='criar_referencia_form'>
      <div class='col-12'>
        <div class='md-form'>
          <input type='text' class='form-control' name='criar_referencia_titulo' id='criar_referencia_titulo' required>
          <label for='criar_referencia_titulo'>Título da nova referência</label>
        </div>
        <div class='md-form'>
        	<input type='text' class='form-control' name='criar_referencia_link' id='criar_referencia_link'>
        	<label for='criar_referencia_link'>Link da nova referência (opcional)</label>
				</div>
        <div class='md-form'>
          <input type='text' class='form-control' name='criar_referencia_autor' id='criar_referencia_autor'>
          <label for='criar_referencia_autor'>Autor da nova referência (opcional)</label>
          <button type='button' class='$button_classes' id='trigger_buscar_autores'>Buscar</button>
        </div>
        <div class='row' id='autores_disponiveis'>
        </div>
        <select class='mdb-select md-form' name='criar_referencia_tipo' id='criar_referencia_tipo'>
          <option value='' disabled selected>Tipo da nova referência:</option>
          <option value='referencia'>Materia de leitura: livros, artigos, páginas virtuais etc.</option>
          <option value='video'>Material videográfico: vídeos virtuais, filmes etc.</option>
          <option value='album_musica'>Material em áudio: álbuns de música, podcasts</option>
        </select>
        <div class='row justify-content-center'>
          <button type='button' class='$button_classes' id='trigger_adicionar_referencia'>$adicionar_referencia_form_botao</button>
				</div>
      </div>
    </div>
  ";