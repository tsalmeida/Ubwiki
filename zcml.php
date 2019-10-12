<!DOCTYPE html>
<!--
 ________   ____              __
/\_____  \ /\  _`\    /'\_/`\/\ \
\/____//'/'\ \ \/\_\ /\      \ \ \
     //'/'  \ \ \/_/_\ \ \__\ \ \ \  __
    //'/'___ \ \ \L\ \\ \ \_/\ \ \ \L\ \
    /\_______\\ \____/ \ \_\\ \_\ \____/
    \/_______/ \/___/   \/_/ \/_/\/___/

@author Stephen Rhyne  Note:(Micro Templating by John Resig)
@twitter srhyne
@email stephen@stephenrhyne.com
@license Do whatever you want with this..
-->
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title>ZCML Sample</title>
		<style type="text/css" media="screen">
			table{
				margin:0px auto;
				border-collapse:collapse;
				margin-bottom:50px;
				-webkit-box-shadow:0px 2px 3px rgba(0,0,0,.3);
			}
			table th, table td{
				padding:3px 3px 3px 6px;
				text-shadow:0px 1px 1px rgba(0,0,0,.2);
			}
			table th{
				background:#e1e1e1;
				border-bottom:1px solid rgba(0,0,0,.1);
			}
			table td{
				background:#eee;
				border-top:1px solid rgba(255,255,255,.8);
				border-bottom:1px solid rgba(0,0,0,.1);
			}
		</style>
	</head>

	<!-- Using router firmware data from http://www.sputnik.com/support/download/ -->
	<body>
		<table>
			<thead>
				<tr>
					<th>Download</th>
					<th>Requires DDWRT Activate</th>
					<th>Flash Method</th>
					<th>Type</th>
					<th>Import ID</th>
					<th>Agent Version</th>
					<th>Subtype</th>
					<th>Sputnik Default</th>
					<th>Version</th>
				</tr>
			</thead>

			<script type="text/zcml" elName="zc-component" id="firmwares" viewLinkName="firmware_list" params=""
				appLinkName="sputnik" sharedBy="sputnikwifi" adminuser="sputnik"
				privateLink="hfnjhD5N1RsSNyMmfB4CSVxgkD8gx3aOwyeN2St43uSt6zSfpXPt8VU52s8w8K8huXkTrX1JCaqtFPgWOYADrBNyy6F2UbYZUu56">
        <%{
        	//PARAMETROS:
        	//pag_str = materia, texto, indice
        	//https://app.zohocreator.com/marciliofilho/ubique/#Page:UBWIKI?alu_str=cavalcanti@me.com&mat_int=0&pag_str=texto&tag_int=3320477000008492194
        	alu_rec = Alunos[email == tostring(alu_str)];
        	link_zoho = "https://creator.zohopublic.com/marciliofilho/ubique/page-embed/UBWIKI/uOnXDu8aUhYfeQb96gNy6JYs85fg2YgspqZNgC9WYxMNh3mgemS3P5GyHKmsYTNSk1JxaQk4ukrhrOg3jhWPjVzn3nnUMJSSmN7Y?alu_str=" + alu_rec.email + "&pag_str=texto&tag_int=";
        	ref_a = "<a href=" + link_zoho;
        	ref_aa = "<a style=\"color:black;\" href=" + link_zoho;
        	ref_b = ">";
        	ref_c = "</a>";
        	conc_rec = CONCURSOS[ID == alu_rec.concurso];
        	empr_rec = EMPRESAS[concursos == conc_rec.ID];
        	conc_mats = CONCURSOS[ID == alu_rec.concurso].materias.getAll();
        	mats_etis = ETIQUETAS[materia == conc_mats].distinct(materia);
        	mats_conc_rec = MATERIAS[ID == conc_mats && ID == mats_etis];
        	//mat_rec = CONCURSOS[ID == alu_rec.concurso].materias.get(tolong(mat_int));
        	//mat_rec = MATERIAS[ID == mat_rec];
        	mat_rec = MATERIAS[ID == tolong(mat_int) || ID == ETIQUETAS[ID == tolong(tag_int)].materia];
        	link_indice = "https://creator.zohopublic.com/marciliofilho/ubique/page-embed/UBWIKI/uOnXDu8aUhYfeQb96gNy6JYs85fg2YgspqZNgC9WYxMNh3mgemS3P5GyHKmsYTNSk1JxaQk4ukrhrOg3jhWPjVzn3nnUMJSSmN7Y?alu_str=" + alu_rec.email + "&pag_str=indice";
        	icon_pin_link = "https://img.icons8.com/ios-filled/50/000000/pin.png";
        	link_indice_materia = link_indice + "&mat_int=" + mat_rec.ID;
        	tag_ct = ITENS[edital.orgao == alu_rec.concurso && questao.materias == mat_rec.ID].etiquetas.getAll().size();
        	tag_rec = ETIQUETAS[ID == tolong(tag_int)];
        	subtag_rec = SUBETIQUETAS[etiqueta == tag_rec.ID] sort by ordem Asc;
        	cor1 = empr_rec.cor1;
        	cinza = "#f3f1ef";
        	largura = "105ch";
        	azulado = "#4B8178";
        	%>
        <!DOCTYPE HTML>
        <html>
        <head>

        <style>

        @import url('https://fonts.googleapis.com/css?family=Lato|Playfair+Display|Playfair+Display+SC&display=swap');
        @import url('https://fonts.googleapis.com/css?family=Fira+Sans:400,700&display=swap');

        *{
        box-sizing: border-box;
         -webkit-overflow-scrolling: touch;

        }


        body{
         overflow: hidden;
         scroll: no;
        }

        textarea {
          width: 100%;
          height: 10em;
          padding: 1ch;
          box-sizing: border-box;
          border: 2px solid <%=cinza%>;
          border-radius: 4px;
          background-color: <%=cinza%>;
          resize: none;
          font-size: 1rem;
        }
        textarea:focus {
           outline: none !important;
           border-color: <%=cinza%>;
           box-shadow: 0 0 10px #a28b62;
        }
        div.row {
        	margin: 3vh 0 0 0;

        }

        col1 {
        width: 96%;
        float: left;
        margin-bottom: -1vh;
        }

        col2 {
        width: 4%;
        float: left;
        padding-top: 1em;
        margin-bottom: -1vh;
        }


        .row:after {
          content: "";
          display: table;
          clear: both;
        }

        .cont {
        	width: <%=largura%>;
        	margin: auto;
        }
        .cont_wrapper {

        }
        .cont_imagem_subsecao {

        }


        .titulo_cont {
        	margin: auto;
        	padding-top: 1.5vh;
        	padding-bottom: 1.5vh;
        	background-color: <%=cinza%>;
        	border-bottom: 1px solid <%=cor1%>
        }


        h1, h2, b{
        font-family: 'Fira Sans', sans-serif;
        }

        h1 {
        	margin-bottom: -2vh;
        	font-family: Playfair Display, serif;
        }

        a {
        color: <%=azulado%>
        cursor: pointer;
        }

        p, li, span, font, em, b {
        font-family: 'Fira Sans', sans-serif;
        font-weight: 400;
        text-align: left;
        font-size: 1rem;
        }

        .legenda_imagem {
        font-size: 1rem;
        font-family: 'Fira Sans', sans-serif;
        color: <%=cor1%>;
        font-style: italic;
        text-align: right;
        margin-top: -0.1vh;
        margin-bottom: 0;
        }

        li.indice {
        color: black;
        font-size: 1rem;
        width: <%=largura%>
        word-break: break-word;
        white-space: normal;
        padding-right: 2vw;
        list-style: none;
        }

        li.indice:hover {
        color: <%=azulado%>;
        }

        div {
        font-family: 'Fira Sans', sans-serif;
        }


        ul {
        list-style: none;
        padding-left: 1vw;
        }

        a:link {
        	color: <%=azulado%>;
        	font-family: 'Fira Sans', sans-serif;
        }

        a:hover {
        	color: <%=cor1%>;
        	text-decoration: underline;
        }

        a.top_index, a.top_index:link {
        	color: <%=cor1%>;
        	font-family: 'Fira Sans', sans-serif;
        	font-size: 1rem;
        }

        a.top_index:hover {
        	color: <%=azulado%>;
        }



        hr {
        border-top: 1px solid <%=cor1%>;
        }
        input[type=submit]{
          background-color: #A28B62; /* Green */
          border-radius: 0.3em;
          width: auto;
          border: none;
          color: white;
          padding: 0.6em 1em;
          text-align: center;
          text-decoration: none;
          display:inline-block;
          font-size: 1rem;
          margin: auto;
          -webkit-transition-duration: 0.4s; /* Safari */
          transition-duration: 0.4s;
          cursor: pointer;
          vertical-align: middle;
        }

        input[type=submit]:hover{
         background-color: #A28B62;
          color: white;
          box-shadow: 0 1em 1.3em 0 rgba(0,0,0,0.24), 0 1.5em 3em 0 rgba(0,0,0,0.19);
        }

        .tooltip {
          position: relative;
          display: inline-block;

        }

        .tooltip .tooltiptext {
          visibility: hidden;
          width: 20ch;
          background-color: #A28B62;
          color: #fff;
          text-align: center;
          border-radius: 6px;
          padding: 5px 0;
          position: absolute;
          z-index: 1;
          bottom: 150%;
          left: 50%;
          margin-left: -10ch;
          font-size: 1rem;
          font-weight: 500;
        }

        .tooltip .tooltiptext::after {
          content: "";
          position: absolute;
          top: 100%;
          left: 50%;
          margin-left: -5px;
          border-width: 5px;
          border-style: solid;
          border-color: black transparent transparent transparent;
        }

        .tooltip:hover .tooltiptext {
          visibility: visible;
        }



        .texto_menor {
        font-family: 'Fira Sans', sans-serif;
        text-align: justify;
        font-size: 1rem;
        font-weight: 300;
        color: white;
        }

        .tooltip1 {
          position: relative;
          display: inline-block;
        }
        .tooltip1 .tooltiptext1 {
          visibility: hidden;
          background-color: #A28B62;
          color: white;
          text-align: justify;
          border-radius: 6px;
          position: absolute;
          z-index: 1;
          bottom: 150%;
          left: 50%;
          font-size: 1rem;
          width: 50ch;
          margin-left: -15vw;
          font-family: Fira Sans, sans-serif;
        }
        .tooltip1 .tooltiptext1::after {
          content: "";
          position: absolute;

        }
        .tooltip1:hover .tooltiptext1 {
          visibility: visible;
        }

        div.sticky {
          position: -webkit-sticky;
          position: sticky;
          top:0;
          background-color: white;
        }

        #footer {
        height: 15vh;
        border-top: solid 2px <%=cor1%>;
        padding: 2vh 0 6vh 0;
        text-align: center;
        background-color: <%=cinza%>;
        margin-top: 2vh;
        overflow-x: hidden;
        }



        #cadernoaluno {
        	background-color: <%=cinza%>;
        	margin-left: -40vw;
        	margin-right: -40vw;
        	padding-right: 40vw;
        	padding-left: 40vw;
        	padding-bottom: 5vh;
        	padding-top: 2vh;
        	border: none;
        }



        .wrapper {
            max-width: 100ch;
            width: 100%;
            margin: 5vh auto;
        }


        input {
            display: none;
        }

        label {
            display: flex;
            width: 100%;
            height: 50px;
            cursor: pointer;
            border: 3px solid #3E474F;
            user-select: none;
        }

        label div:first-child {
            width: 100%;
            line-height: 45px;
            margin-left: 10px;
            font-size: 1em;
        }

        .cross{
            margin-right:15px;
            margin-top:3px;
        }

        .cross:before,.cross:after {
            content: '';
            border-top: 2px solid #3E474F;
            width: 15px;
            display: block;
            margin-top: 18px;
            transition: 0.3s;
        }

        .cross:after {
            transform: rotate(90deg);
            margin-top: -2px;
        }

        .content {
            box-sizing: border-box;
            font-size: 1em;
            margin: 10px 10px;
            max-height: 0;
         overflow: hidden;
            transition: max-height, .5s;
        }

        input:checked ~ .content {
            max-height: 60000vh;
            transition: max-height, 1s;
        }

        input:checked ~ label .cross:before {
            transform: rotate(180deg);
        }

        input:checked ~ label .cross:after {
            transform: rotate(0deg);
        }

        .questions{
            margin-top:20px;
            max-height: 0;
         overflow: hidden;
            transition: max-height, .5s;
        }

        .questions label{
            border:none;
            box-shadow: none;
            margin:0;
        }
        input:checked ~ .questions {
            <!-- max-height: 400px; -->
            border-bottom:2px solid #3E474F;
            transition: 1s;
        }
        .tip {
            color: #f03768;
            cursor: pointer;
            position: relative;
          overflow: visible;
            font-size: 1em;
        }

        .tip:before,
        .tip:after {
            position: absolute;
            opacity: 0;
            z-index: -100;
            transform: translateY(-30%);
            transition: .4s;
        }

        .tip:before {
            content: '';
            border-style: solid;
            border-width: 0.8em 0.5em 0 0.5em;
            border-color: #3E474F transparent transparent transparent;
            transform: translateY(-200%);
            bottom:90%;
            left:50%;
        }

        .tip:after {
            content: attr(data-tip);
            background: #3E474F;
            color: white;
            width: 20ch;
            padding: 10px;
            font-size: 1em;
            bottom: 150%;
            left: -50%;
        }

        .tip:hover:before,
        .tip:hover:after {
            opacity: 1;
            z-index: 100;
            transform: scaleY(1);
        }





















        </style>
        </head>
        <body>
        <%
        	if(pag_str == "termos")
        	{
        		%>
        <div class="titulo_cont">
        <div class="cont">
        <a href='<%=link_indice%>' class="top_index">Índice</a>
        </div>
        </div>
        <div class="cont">
        <div class="cont_wrapper" style="padding-bottom: 5vh; padding-top: 2vh;">
        <h1>Termos e condições de uso da Ubwiki</h1>
        <h2>O que é a Ubwiki</h2>
        <hr>
        <p>A Ubwiki é uma enciclopédia digital que tem o objetivo de reunir, de maneira gratuita e democrática, todo o conhecimento necessário para a aprovação no <%=conc_rec.concurso%>, seja na forma de verbetes escritos pelos próprios usuários, seja pela indicação de vídeos, aulas ou links externos. Quanto maior o envolvimento da comunidade dos candidatos, mais rica, completa e útil será a base de verbetes. Os organizadores da Ubwiki convidam todos os estudantes e professores, inclusive de empresas concorrentes, para mostrarem seu trabalho e contribuirem nesse projeto com aulas e com atualizações dos verbetes.</p>
        <p>Os verbetes foram criados com base na estrutura do conteúdo programático constante de cada matéria do concurso. Novos verbetes podem ser criados por sugestão dos usuários, caso necessário.</p>
        <h2>Próprias apostilas.</h2>
        <hr>
        <p>O usuário da Ubwiki terá a disposição, também de forma gratuita, um sistema de registro de suas próprias apostilas sobre o verbete estudado. O corpo técnico do Grupo Ubique entende que, consoante ao princípio da especificidade, a forma mais eficiente de se capacitar para a aprovação no <%=conc_rec.concurso%> é estudar da forma como você será cobrado(a) na prova. Nenhum outro método de estudos pode ter a mesma eficácia.</p>
        <p>Outra contribuição do sistema é no tocante à organização de seu conhecimento: você pode associar aos verbetes da página não só seus próprios textos, que incorporam sua compreensão e entendimento de cada tema, mas anotações e dados brutos, retirados de suas leituras, no entendimento de que cada novo conhecimento será tão mais acessível quanto mais relacionado a outras informações já conhecidas.</p>
        <h2>Regras de estilo da escrita dos verbetes</h2>
        <hr>
        <p>Sugere-se que os artigos sejam escritos com a vocabulário pertinente à matéria, obedecendo sempre à modalidade culta do idioma, em linguagem clara e imparcial. Opiniões do candidato ou as que sejam reproduzidas a partir de pontos de vistas de autores podem e devem ser incluídas, com a devida ressalva. Procure indicar a origem de citações e de opiniões controversas sobre o assunto.</p>
        <h2>Banimento</h2>
        <hr>
        <p>Está previsto o banimento do uso da página e do sistema ao usuário que, mediante denúncia: a) faça comentários ofensivos ou dirija agressões verbais a outros usuários na área de debates; b) atue no sentido de prejudicar a qualidade dos verbetes já escritos, seja apagando trechos importantes ou polêmicos, seja aplicando linguagem inapropriada ao meio.</p>
        <h2>Condições de uso</h2>
        <hr>
        <p>O Grupo Ubique se reserva o direito de uso de toda informação contida na base de dados do sistema, sem fins comerciais, estando livre a divulgação, reprodução ou edição de qualquer texto, aberto ou não, por parte da empresa.</p>
        <p>O usuário, ao utilizar o sistema, concorda com acesso, para fins de suporte técnico, a seus textos privados pelo Grupo Ubique.</p>
        <%
        	}
        	//*******ÍNDICE GERAL
        	//*******************
        	if(pag_str == "indice" || pag_str == "materia")
        	{
        		%>
        <!-- <span class="tip" data-tip="Lorem ipsum dolor sit amet.">deserunt</span> -->


        <div class="wrapper">
        <h1 style="margin-top: -2vh; margin-bottom: 3vh;">Índice geral</h1>
        <%
        		k = 1;
        		for each  mate in mats_conc_rec
        		{
        			ide = "tab-" + k;
        			checa = if(mate.ID == tolong(mat_rec.ID),"checked","");
        			%>
        <div class="wrap-1">
        		<input id='<%=ide%>' name="tabs" type="checkbox" <%=checa%>/>
        		<label for='<%=ide%>'><div style="font-family: 'Fira Sans', sans-serif;"><%=mate.materia%></div><div class="cross"></div></label>
        		 <div class="content">
        <%
        			k_h_1 = 1;
        			for each  tema in ETIQUETAS[materia == mate.ID && ordem = 1]
        			{
        				link_etiqueta = link_zoho + tema.ID;
        				apos = MINHAS_APOSTILAS[etiqueta == tema.ID && aluno == alu_rec.ID];
        				ico_pin = if(apos.count() > 0,"<img src='" + icon_pin_link + "' style=\"width:16px; height:16px\" alt=\"icone\">","");
        				%>
        <a href='<%=link_etiqueta%>'><li class="indice" style="margin-top: 2vh; margin-left: 4ch; text-indent: -4ch; font-weight: bold; "><%=k_h_1%>&nbsp;<%=tema.etiqueta%> &nbsp;<%=ico_pin%></li></a>
        <%
        				k_h_2 = 1;
        				for each  tema1 in ETIQUETAS[materia == mate.ID && etiqueta == tema.subordinados]
        				{
        					link_etiqueta1 = link_zoho + tema1.ID;
        					apos = MINHAS_APOSTILAS[etiqueta == tema1.ID && aluno == alu_rec.ID];
        					ico_pin = if(apos.count() > 0,"<img src='" + icon_pin_link + "' style=\"width:16px; height:16px\" alt=\"icone\">","");
        					%>
        <a href='<%=link_etiqueta1%>'><li class="indice" style="padding-left: 2ch; margin-left: 5ch; text-indent: -5ch;"><%=k_h_1%>.<%=k_h_2%>.&nbsp;<%=tema1.etiqueta%> &nbsp;<%=ico_pin%></li></a>
        <%
        					k_h_3 = 1;
        					for each  tema2 in ETIQUETAS[materia == mate.ID && etiqueta == tema1.subordinados]
        					{
        						link_etiqueta2 = link_zoho + tema2.ID;
        						apos = MINHAS_APOSTILAS[etiqueta == tema2.ID && aluno == alu_rec.ID];
        						ico_pin = if(apos.count() > 0,"<img src='" + icon_pin_link + "' style=\"width:16px; height:16px\" alt=\"icone\">","");
        						%>
        <a href='<%=link_etiqueta2%>'><li class="indice" style="padding-left: 6ch; margin-left: 6ch; text-indent: -6ch;"><%=k_h_1%>.<%=k_h_2%>.<%=k_h_3%>.&nbsp;<%=tema2.etiqueta%> &nbsp;<%=ico_pin%></li></a>
        <%
        						k_h_4 = 1;
        						for each  tema3 in ETIQUETAS[materia == mate.ID && etiqueta == tema2.subordinados]
        						{
        							link_etiqueta3 = link_zoho + tema3.ID;
        							apos = MINHAS_APOSTILAS[etiqueta == tema3.ID && aluno == alu_rec.ID];
        							ico_pin = if(apos.count() > 0,"<img src='" + icon_pin_link + "' style=\"width:16px; height:16px\" alt=\"icone\">","");
        							%>
        <a href='<%=link_etiqueta3%>'><li class="indice" style="padding-left: 9ch; margin-left: 6ch; text-indent: -6ch;"><%=k_h_1%>.<%=k_h_2%>.<%=k_h_3%>.<%=k_h_4%>.&nbsp;<%=tema3.etiqueta%> &nbsp;<%=ico_pin%></li></a>
        <%
        							k_h_4 = k_h_4 + 1;
        						}
        						k_h_3 = k_h_3 + 1;
        					}
        					k_h_2 = k_h_2 + 1;
        				}
        				k_h_1 = k_h_1 + 1;
        			}
        			%>
        </div>
        </div>
        <%
        			k = k + 1;
        		}
        		%>
        </div>
        <%
        	}
        	//PÁGINA PRINCIPAL DO TEMA   ******************
        	else if(pag_str == "texto")
        	{
        		%>
        <div class="titulo_cont">
        <div class="cont">


        <div class="row">
        	<div style="width: 99%; float: left; bottom: 0px; padding-top: 0vh;">
        		<a href='<%=link_indice%>' class="top_index">Índice</a><a href='<%=link_indice_materia%>' class="top_index">&nbsp→ <%=mat_rec.materia%></a><a class="top_index">&nbsp→ <%=tag_rec.superior%></a>
        	</div>
        	<div style="width: 1%; float: left; padding-top: 1em; text-align: right;">
        	</div>
        </div>



        </div>
        </div>
        <div class="cont">
        <div class="cont_wrapper">
        <div style="font-size: 3rem; user-select: none; margin-top: 2vh; font-family: Playfair Display, serif; font-weight: 900; color: <%=cor1%>; text-align: left;">UBWIKI</div>
        <%
        		icon_add_subcp = "https://img.icons8.com/ios-glyphs/30/000000/add-list.png";
        		link_form_subcp = "https://creator.zohopublic.com/marciliofilho/ubique/form-embed/TEXTOS_UBWIKI_SUBSECOES/uzwy61XdKrsESkrGmTDxU9FgRZ4O5zNjdO1QeNd4ODwNPJwvyNqXkCaWWheGp8v3MG2BOe6Y3rxtQ1ED7vvJkdgZHyKmZ0Ss3ynu?etiqueta=" + tag_rec.ID + "&aluno=" + alu_rec.ID;
        		icon_add_subcp_img = "<a href=\"" + link_form_subcp + "\"><img src=\"" + icon_add_subcp + "\" style=\"width:36px; height:36px\" alt=\"icone\"></a>";
        		%>
        <div class="row" style="margin-top: 1vh;">
        	<div style="width: 96%; float: left;">
        		<h1 style="padding-left: 1.5em; text-indent:-1.5em; text-align: left; margin-bottom: 0;"><%=tag_rec.etiqueta%></h1>
        	</div>
        	<div style="width: 4%; float: left; padding-top: 1.5em;">
        <%
        		if(subtag_rec.count() == 0)
        		{
        			%>
        <span class="tip" data-tip="Adicionar subtópico."><%=icon_add_subcp_img%></span>
        <%
        		}
        		%>
        </div>
        </div>
        <hr>
        <%
        		//*****INDICE DE SUBSECOES
        		%>
        <div style="background-color: <%=cinza%>; padding: 1em; width:65%;">
        <ul>
        <%
        		icone_adicionar = "<a href=\"" + link_form_subcp + "\"><img src=\"" + icon_add_subcp + "\" style=\"width:36px; height:36px\" alt=\"icone\"></a>";
        		%>
        <div class="row">
        	<div style="width: 86%; float: left;">
        		<h1>Índice</h1>
        	</div>
        	<div style="width: 14%; float: left; padding: 1vh 2em 0 0;">
        		<span class="tip" data-tip="Adicionar subtópico."><%=icone_adicionar%></span>
        	</div>
        </div>
        <hr style="margin-left: -0.3em; width: 95%">




        <a href="subtag1"><li style="color: <%=azulado%>;">1 Introdução</li></a>
        <%
        		if(subtag_rec.count() > 0)
        		{
        			cont = 2;
        			for each  setiq in subtag_rec
        			{
        				lk_ss_id = "#subtag" + (cont - 1);
        				%>
        <a href="<%=lk_ss_id%>"><li style="color: <%=azulado%>;"><%=cont%>&nbsp;<%=setiq.subetiqueta%></li></a>
        <%
        				cont = cont + 1;
        			}
        		}
        		%>
        <a href="#relacionados"><li style="color: <%=azulado%>;"><%=cont + 1%>&nbsp;Verbetes relacionados</li></a>
        <a href="#bibliografia"><li style="color: <%=azulado%>;"><%=cont + 2%>&nbsp;Bibliografia pertinente</li></a>
        <a href="#videos"><li style="color: <%=azulado%>;"><%=cont + 3%>&nbsp;Vídeos e aulas relacionados</li></a>
        <a href="#externas"><li style="color: <%=azulado%>;"><%=cont + 5%>&nbsp;Ligações externas</li></a>
        <a href="#anotacoes"><li style="color: <%=azulado%>;"><%=cont + 5%>&nbsp;Minhas anotações</li></a>
        <a href="#questoes"><li style="color: <%=azulado%>;"><%=cont + 4%>&nbsp;Questões de prova e simulados</li></a>
        <a href="#discussao"><li style="color: <%=azulado%>;"><%=cont + 5%>&nbsp;Discussão</li></a>
        </ul>
        </div>
        <%
        		//***********TEXTO PRINCIPAL
        		texto_mod = tag_rec.texto;
        		if(tag_rec.texto != null && tag_rec.texto != "")
        		{
        			for each  etiqueta in ETIQUETAS[materia == tag_rec.materia]
        			{
        				link_eti_inline = "https://creator.zohopublic.com/marciliofilho/ubique/page-embed/UBWIKI/uOnXDu8aUhYfeQb96gNy6JYs85fg2YgspqZNgC9WYxMNh3mgemS3P5GyHKmsYTNSk1JxaQk4ukrhrOg3jhWPjVzn3nnUMJSSmN7Y?tag_int=" + etiqueta.ID + "&alu_str=" + alu_rec.email + "&pag_str=texto";
        				texto_mod = texto_mod.replaceall(etiqueta.etiqueta,"<a href='" + link_eti_inline + "'>" + etiqueta.etiqueta + "</a>");
        			}
        		}
        		existe_texto = TEXTOS_UBWIKI[etiqueta == tag_rec.ID && texto != null && texto != ""];
        		//ult_atualiz = if(existe_texto.count() > 0, "Última atualização em " + TEXTOS_UBWIKI[etiqueta == tag_rec.ID].Modified_Time sort by Modified_Time Desc, "");
        		if(existe_texto.count() > 0)
        		{
        			ult_atualiz = TEXTOS_UBWIKI[etiqueta == tag_rec.ID].Modified_Time sort by Modified_Time Desc;
        			ult_atualiz = "Última atualização em " + ult_atualiz.text("dd/MM/yyyy") + ".";
        		}
        		else
        		{
        			ult_atualiz = "";
        		}
        		icon_edit = "https://library.kissclipart.com/20180919/vpq/kissclipart-edit-icon-transparent-clipart-computer-icons-editi-de5fa5eb855b1ea3.jpg";
        		link_edit_principal = "https://creator.zohopublic.com/marciliofilho/ubique/form-embed/TEXTOS_UBWIKI/s9CWUFHXzUEfdjj3BQv2VjHrK50K46Wf7Y768fEPgQtAa3fOZXMFObF0nMpxmWv0CyFmCvau5z7KTYzGH9wat8zXXSZZzv6Dv9dq?etiqueta=" + tag_rec.ID + "&aluno=" + alu_rec.ID;
        		texto_mod = if(tag_rec.texto == null || tag_rec.texto == "","<b>" + alu_rec.primeiro + "</b>, o texto sobre <em>" + tag_rec.etiqueta + "</em> está louco para ser escrito! Clique " + "<a href='" + link_edit_principal + "'>aqui</a> para ser o primeiro a contribuir para esse valioso projeto!",texto_mod);
        		icon_principal = "<a href=\"" + link_edit_principal + "\"><img src=\"" + icon_edit + "\" style=\"width:36px; height:36px\" alt=\"icone\"></a>";
        		%>
        <div class="sticky">
        <div class="row">
        	<div style="width: 96%; float: left;">
        		<h1>Verbete consolidado</h1>
        	</div>
        	<div style="width: 4%; float: left; padding-top: 1em;">
        		<%=icon_principal%>
        	</div>
        </div>
        <hr>
        </div>




        <li style="list-style: none;"><%=texto_mod.replaceall("<div>","<p>").replaceall("</div>","</p>")%></li>
        <li style="list-style: none; text-align: center; color: #808080; margin-top: 2vh;"><%=ult_atualiz%></li>
        <%
        		contribuidores = "";
        		alu_cont = TEXTOS_UBWIKI[etiqueta == tag_rec.ID].distinct(aluno);
        		alu_contr = TEXTOS_UBWIKI_SUBSECOES[etiqueta == tag_rec.ID].distinct(aluno);
        		alu_contri = Alunos[ID == alu_cont || ID == alu_contr].distinct(ID);
        		for each  contribu in Alunos[ID == alu_contri]
        		{
        			contribuidores = if(contribuidores == "","",contribuidores + ", ") + contribu.nomecompleto;
        		}
        		%>
        <li style="list-style: none; text-align: justify; color: #808080; margin-top: 1vh;">Os usuários da Ubwiki deixam registrado um agradecimento especial àqueles que contribuíram para a construção deste artigo: <%=contribuidores%>.</li>
        <%
        		//********************SUBSECOES
        		//******************************
        		if(subtag_rec.count() > 0)
        		{
        			cont = 1;
        			for each  sr in subtag_rec
        			{
        				link_edit_secundario = "https://creator.zohopublic.com/marciliofilho/ubique/form-embed/TEXTOS_UBWIKI_SUBSECOES/uzwy61XdKrsESkrGmTDxU9FgRZ4O5zNjdO1QeNd4ODwNPJwvyNqXkCaWWheGp8v3MG2BOe6Y3rxtQ1ED7vvJkdgZHyKmZ0Ss3ynu?etiqueta=" + tag_rec.ID + "&aluno=" + alu_rec.ID + "&subetiqueta=" + sr.ID;
        				subtag_id = "subtag" + cont;
        				lk_ed_sec = "<a href=\"" + link_edit_secundario + "\"><img src=\"" + icon_edit + "\"style=\"width:36px; height:36px\"alt=\"icone\"></a>";
        				%>
        <div class="row">
        	<div style="width: 96%; float: left;">
        		<h1 id="<%=subtag_id%>"><%=sr.subetiqueta%></h1>
        	</div>
        	<div style="width: 4%; float: left; padding-top: 1em;">
        		<%=lk_ed_sec%>
        	</div>
        </div>
        <hr>
        <%=sr.texto.replaceall("<div>","<p>").replaceall("</div>","</p>")%>
        <%
        				cont = cont + 1;
        			}
        		}
        		//******************
        		//*********************SEÇÃO DE VERBETES RELACIONADOS
        		link_sug_tag = "https://creator.zohopublic.com/marciliofilho/ubique/form-embed/SUGERIR_VERBETE/aq4NZ7YtXfV8MTRZ74qphkRbJrejW1nvb5Qx97OHh2OGswkEvg3rW3C0gNV6b3HpMRmVN6Y779k65DJ7qeHeC98KEVWSaVKu0jpJ?aluno=" + alu_rec.ID + "&etiqueta=" + tag_rec.ID;
        		icon_sug_tag = "https://img.icons8.com/ios-glyphs/30/000000/add-tag.png";
        		icon_sug_tag = "<a href=\"" + link_sug_tag + "\"><img src=\"" + icon_sug_tag + "\" style=\"width:36px; height:36px\" alt=\"icone\"></a>";
        		%>
        <div class="row">
        	<div style="width: 96%; float: left;">
        		<h1 id="relacionados">Verbetes relacionados</h1>
        	</div>
        	<div style="width: 4%; float: left; padding-top: 1em;">
        		<%=icon_sug_tag%>
        	</div>
        </div>
        <hr>
        <%
        		vt_tag = ETIQUETAS[etiqueta == tag_rec.superior].ID;
        		vertambem = if(tag_rec.superior == null || tag_rec.superior == "" || tag_rec.superior == tag_rec.etiqueta,"",ref_a + vt_tag + ref_b + tag_rec.superior + ref_c);
        		for each  sub in ETIQUETAS[etiqueta == tag_rec.subordinados && materia == tag_rec.materia]
        		{
        			vt_tag = sub.ID;
        			vertambem = vertambem + " | " + ref_a + vt_tag + ref_b + sub.etiqueta + ref_c;
        		}
        		for each  asso in ETIQUETAS[etiqueta == tag_rec.associacoes && materia == tag_rec.materia]
        		{
        			vt_tag = asso.ID;
        			vertambem = vertambem + " | " + ref_a + vt_tag + ref_b + asso.etiqueta + ref_c;
        		}
        		for each  top in ETIQUETAS[ID == TOPICOS_EDITAL[ID == tag_rec.topicoedital].etiquetas && ID != tolong(tag_int)]
        		{
        			vt_tag = top.ID;
        			if(vertambem.notcontains(top.etiqueta))
        			{
        				vertambem = vertambem + " | " + ref_a + vt_tag + ref_b + top.etiqueta + ref_c;
        			}
        		}
        		%>
        <p><%=vertambem%></p>
        <%
        		fontes = FONTES[Capitulos.etiquetas == tag_rec.ID];
        		if(fontes.count() > 0)
        		{
        			link_edit_biblio = "https://creator.zohopublic.com/marciliofilho/ubique/form-embed/SUGERIR_BIBLIOGRAFIA/GJaDJUV2GefjNvX3hCU2VrYRJQVkuOPDfUT6zmeYCAJN5tYvHjnYXdtq2n3upHB5nBAq4mhtRnEmxgEju2GW2YxMpDvfGT72YTET?aluno=" + alu_rec.ID + "&etiqueta=" + tag_rec.ID;
        			icon_biblio = "<a href=\"" + link_edit_biblio + "\"><img src=\"" + icon_edit + "\" style=\"width:36px; height:36px\" alt=\"icone\"></a>";
        			%>
        <div class="row">
        	<div style="width: 96%; float: left;">
        		<h1 id="bibliografia">Bibliografia pertinente</h1>
        	</div>
        	<div style="width: 4%; float: left; padding-top: 1em;">
        		<%=icon_biblio%>
        	</div>
        </div>


        <hr>
        <ul style="padding-left: 0;">
        <%
        			for each  f in fontes
        			{
        				%>
        <li>• <%=f.abnt%></li>
        	<ul style="padding-left: 0;">
        <%
        				for each  cap in CAPITULOS[fonte == fontes.ID && etiquetas == tag_rec.ID]
        				{
        					%>
        <li>— <%=cap.titulo%>, pags. <%=cap.inicial%>—<%=cap.final%>.</li>
        <%
        				}
        				%>
        </ul>
        	</li>
        <%
        			}
        			%>
        </ul>
        <%
        		}
        		base_link = "https://creator.zohopublic.com/marciliofilho/ubique/form-embed/LINKS_ETIQUETAS/OSN8X52hFb3WzHQ70rd1ZusVbVC1zQqmp5KkM722E28SkQ8YzxAz5VYrEnA3sO9Ab9QwDHY1b0FftxgHwSnwYp0PGTdggsMAQO2e?";
        		link_video = LINKS_ETIQUETAS[etiqueta == tag_rec.ID && tipo == "Vídeo"];
        		link_edit_videos = base_link + "tipo=Vídeo&etiqueta=" + tag_rec.ID + "&aluno=" + alu_rec.ID;
        		icon_videos = "<a href=\"" + link_edit_videos + "\"><img src=\"" + icon_edit + "\" style=\"width:36px; height:36px\" alt=\"icone\"></a>";
        		%>
        <div class="row">
        	<div style="width: 96%; float: left;">
        		<h1 id="videos">Vídeos e aulas relacionados</h1>
        	</div>
        	<div style="width: 4%; float: left; padding-top: 1em;">
        		<%=icon_videos%>
        	</div>
        </div>
        <hr>
        <%
        		if(link_video.count() > 0)
        		{
        			for each  vid in link_video
        			{
        				%>
        <h2><%=vid.titulo%></h2>
        <iframe width="300px" height="180px" src="https://www.youtube.com/embed/<%=vid.link%>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        <%
        			}
        		}
        		else
        		{
        			%>
        <p>Ainda não há vídeos ou aulas públicas associados a este tema. Clique <a href='<%=link_edit_videos%>'>aqui</a> para adicionar um.</p>
        <%
        		}
        		link_edit_links = base_link + "tipo=Link&etiqueta=" + tag_rec.ID + "&aluno=" + alu_rec.ID;
        		icon_links = "<a href=\"" + link_edit_links + "\"><img src=\"" + icon_edit + "\" style=\"width:36px; height:36px\" alt=\"icone\"></a>";
        		%>
        <div class="row">
        	<div style="width: 96%; float: left;">
        		<h1 id="externas">Ligações externas</h1>
        	</div>
        	<div style="width: 4%; float: left; padding-top: 1em;">
        		<%=icon_links%>
        	</div>
        </div>
        <hr>
        <%
        		link_link = LINKS_ETIQUETAS[etiqueta == tag_rec.ID && tipo == "Link"];
        		if(link_link.count() > 0)
        		{
        			%>
        <ul style="padding-left: 0;">
        <%
        			for each  ligext in link_link
        			{
        				%>
        <li style="list-style: none;"><a href="<%=ligext.link%>" target="_blank"><%=ligext.titulo%></a></li>
        <%
        			}
        			%>
        </ul>
        <%
        		}
        		else
        		{
        			%>
        <p>Ainda não há links externos associados a este tema. Clique <a href='<%=link_edit_links%>'>aqui</a> para adicionar um.</p>
        <%
        		}
        		//APOSTILA
        		apostila = MINHAS_APOSTILAS[aluno == alu_rec.ID && etiqueta == tag_rec.ID];
        		//next_url_apost = "&zc_NextUrl=https://creator.zohopublic.com/marciliofilho/ubique/page-perma/UBWIKI/uOnXDu8aUhYfeQb96gNy6JYs85fg2YgspqZNgC9WYxMNh3mgemS3P5GyHKmsYTNSk1JxaQk4ukrhrOg3jhWPjVzn3nnUMJSSmN7Y?alu_str="+ alu_rec.email + "&pag_str=texto&tag_int=" + tag_rec.ID;
        		link_edit_apos = if(apostila.count() == 0,"https://creator.zohopublic.com/marciliofilho/ubique/form-embed/MINHAS_APOSTILAS/gjHEyRe1KupSqGQw0Oa6GAUgy3F5ddVpZXOqCQWveJWqFqyBhbfTNbeX9MQmGeNvwWaAfqtuCJXFWfQAMymvgCR2p4M2g0eF6hZn?aluno=" + alu_rec.ID + "&etiqueta=" + tag_rec.ID,"https://creator.zohopublic.com/marciliofilho/ubique/MINHAS_APOSTILAS/record-edit/MINHAS_APOSTILAS_RPT/" + apostila.ID + "/Pr37VCp4M2DXN0weC0DjwABT4vSx3ZJA09zkSWzfCE4k9ZtBxkytHy8ZSWmYR5HpC8HDWKTOxg8BH0TBpfQ1D4YYERWFwrvrZAfH");
        		icon_apos = "<a href=\"" + link_edit_apos + "\"><img src=\"" + icon_edit + "\" style=\"width:36px; height:36px\" alt=\"icone\"></a>";
        		%>
        <div id="cadernoaluno">


        <div class="row">
        	<div style="width: 96%; float: left;">
        		<h1 id="anotacoes">Minhas anotações</h1>
        	</div>
        	<div style="width: 4%; float: left; padding-top: 1em;">
        		<%=icon_apos%>
        	</div>
        </div>

        <hr style="border: 1px solid black; margin-bottom: 2vh;">
        <%
        		if(apostila.count() > 0)
        		{
        			str_dados = apostila.dados;
        			if(apostila.dados.contains("font-size:"))
        			{
        				str_dados = apostila.dados.substring(apostila.dados.find("font-size:"),apostila.dados.find("font-size:") + 15);
        				str_dados = apostila.dados.replaceall(str_dados,"font-size: 1rem").replaceall("(255, 255, 255)","(243, 241, 239)");
        			}
        			str_opi = apostila.opiniao;
        			if(apostila.opiniao.contains("font-size:"))
        			{
        				str_opi = apostila.opiniao.substring(apostila.opiniao.find("font-size:"),apostila.opiniao.find("font-size:") + 15);
        				str_opi = apostila.opiniao.replaceall(str_opi,"font-size: 1rem").replaceall("(255, 255, 255)","(243, 241, 239)");
        			}
        			str_texto = apostila.texto;
        			if(apostila.texto.contains("font-size:"))
        			{
        				str_texto = apostila.texto.substring(apostila.texto.find("font-size:"),apostila.texto.find("font-size:") + 15);
        				str_texto = apostila.texto.replaceall(str_texto,"font-size: 1rem").replaceall("(255, 255, 255)","(243, 241, 239)");
        			}
        			%>
        <li style="list-style: none; margin-bottom: 3vh;"><b>Dados:</b><br><%=str_dados%></li>
        <li style="list-style: none; margin-bottom: 3vh;"><b>Opinião & citações:</b><br><%=str_opi%></li>
        <li style="list-style: none;"><b>Meu texto: </b><br><%=str_texto%></li>
        <%
        		}
        		else
        		{
        			xxx = "Você ainda não começou a produzir sua própria apostila sobre este tema! Clique <a href='" + link_edit_apos + "'>aqui</a> para dar esse importante passo na sua preparação!";
        			%>
        <li style="list-style: none;"><%=xxx%></li>
        <%
        		}
        		%>
        </div>
        <%
        		//**********
        		//*********
        		//***SEÇÃO DAS QUESTÕES
        		lk_simu = "https://creator.zohopublic.com/marciliofilho/ubique/page-embed/SIMULADO/N6sY69NStew7kD47Z9JJ4EZnO1m16Eez3p4RDVUpHGqCdg42EpdVwPbB3K1pQnBGvUZ5xk4POA15NVtP1AXsSfV6NYsM6AHQsKRk?tipo_str=etiqueta&alu_str=" + alu_rec.email + "&tag_id=" + tag_rec.ID + "&from_int=1";
        		link_icon_simu = "https://img.icons8.com/pastel-glyph/64/000000/test-partial-passed.png";
        		icon_simu = "<a href=\"" + lk_simu + "\"><img src=\"" + link_icon_simu + "\" style=\"width:36px; height:36px\" alt=\"icone\"></a>";
        		%>
        <div class="row">
        	<div style="width: 96%; float: left;">
        		<h1 id="questoes">Questões de prova e simulado sobre o tema</h1>
        	</div>
        	<div style="width: 4%; float: left; padding-top: 1em;">
        		<%=icon_simu%>
        	</div>
        </div>
        <hr>
        <p>
        <%
        		kk = 1;
        		for each  q in QUESTOES[Itens.etiquetas == tag_rec.ID]
        		{
        			symb = "&nbsp;•&nbsp";
        			%>
        <div class="tooltip1">
        <span><%=symb%>Questão nº <%=q.numeroquestao%> (<%=q.edital.sigla%> <%=q.edital.ano%>)</span>
          				<span class="tooltiptext1">



        <div style="background-color: #a28b62; padding: 1vh 1.5vw; border-radius: 5px; font-family: 'Fira Sans', sans-serif; font-weight: 300;">
        <h2 style="color: white; text-align: center;">Questão nº <%=q.numeroquestao%> (<%=q.edital.sigla%> <%=q.edital.ano%>)</h2>
        <%
        			if(q.textodeapoio != null)
        			{
        				%>
        <!-- <p class="texto_menor"><%=q.textodeapoio.texto%></p> -->
        <%
        			}
        			if(q.textodeapoio.imagem != null && q.textodeapoio.imagem != "")
        			{
        				%>
        <!-- <img src='<%=q.textodeapoio.imagem%>'> -->
        <%
        			}
        			%>
        <p class="texto_menor"><%=q.enunciado%></p>
        <%
        			for each  item in ITENS[questao == q.ID] sort by item Asc
        			{
        				%>
        <p class="texto_menor"><%=item.item%>)&nbsp;<%=item.enunciado%></p>
        <%
        			}
        			%>
        </div>
        </span>
        </div>
        <%
        			kk = kk + 1;
        		}
        		%>
        </p>
        <%
        		//****************
        		//****************
        		//*****FIM DA ÁREA DAS QUESTÕES
        		//
        		//
        		//
        		//
        		//*********SEÇÃO DA DISCUSSÃO*********************
        		link_edit_student = "https://creator.zohopublic.com/marciliofilho/ubique/Alunos/record-edit/ALUNOS_RPT/" + alu_rec.ID + "/dRQAQRrVYGs5hnuvn9jQfOwZVY0vNBwUjfW3SZYJn2mjBSAvyMDhyyaCnkMqNVr32GMXm7nAKXs7ytUSQEXptWbVuwz1tg9r4Dan";
        		icon_add_photo_link = "https://img.icons8.com/material-rounded/26/000000/edit-user-male.png";
        		icon_add_photo = "<a href=\"" + link_edit_student + "\"><img src=\"" + icon_add_photo_link + "\" style=\"width:36px; height:36px\" alt=\"icone\"></a>";
        		formaction = "https://creator.zohopublic.com/marciliofilho/ubique/form-embed/DISCUSSAO/FMwM0ftMU6fDgBAJPgg7Xyk7Vgsw6dg31PuDhWQJgr1AGWGOva3MAxppexeGJWGCZXCQpG9KqCBgA7juB5k15rmqfmHgbG8vEUQK?aluno=" + alu_rec.ID + "&etiqueta=" + tag_rec.ID;
        		%>
        <div class="row">
        	<div style="width: 96%; float: left;">
        		<h1 id="discussao">Discussão</h1>
        	</div>
        	<div style="width: 4%; float: left; padding-top: 1em;">
        		<%=icon_add_photo%>
        	</div>
        </div>
        <hr style="margin-bottom: 2vh;">
        <%
        		alt_formaction = "https://creator.zoho.com/api/marciliofilho/xml/ubique/form/DISCUSSAO/record/add";
        		//https://creator.zoho.com/api/sampleapps/xml/sample/form/Employee/record/add
        		//alt_formaction = 10abe2d1aae94281cff517bb019aea2b;
        		//placeholder="Deixe seus comentários..."
        		%>
        <form action= <%=alt_formaction%> method="post" id="discussao">
        	<input type="hidden" name ="authtoken" value="10abe2d1aae94281cff517bb019aea2b">
        	<input type="hidden" name ="scope" id="scope" value="creatorapi">
        	<input type="hidden" name ="aluno" value="<%=alu_rec.ID%>">
        	<input type="hidden" name ="dataehora" value="<%=now%>">
        	<input type="hidden" name ="etiqueta" value="<%=tag_rec.etiqueta%>">
          <textarea id="meuscomentarios" name="comentario" form="discussao">Comente aqui!</textarea>
          <button type="submit" value="COMENTAR" style="margin-top: 1vh;">Publicar comentário</button>
        </form>
        <%
        		discussao_form = DISCUSSAO[etiqueta == tag_rec.ID] sort by dataehora Desc;
        		if(discussao_form.count() > 0)
        		{
        			%>
        <ul style="padding-left: 0;">
        <%
        			for each  disc in discussao_form
        			{
        				alu_disc = Alunos[ID == discussao_form.aluno];
        				lk_img_ukn = "https://cdn1.vectorstock.com/i/thumb-large/16/05/male-avatar-profile-picture-silhouette-light-vector-5351605.jpg";
        				foto = alu_disc.foto.replaceall("sharedBy","marciliofilho").replaceall("appLinkName","ubique").replaceall("viewLinkName","ALUNOS_RPT").replaceall("fieldName","foto").replaceall("<img","<img style=\"border-radius: 90%\" width=\"50\" height=\"50\"");
        				foto = if(alu_disc.foto == null || alu_disc.foto == "","<img style=\"border-radius: 90%\" width=\"50\" height=\"50\" src=\"" + lk_img_ukn + "\">",foto);
        				//"https://creatorexport.zoho.com/sharedBy/appLinkName/viewLinkName/fieldName/image/1569247648296_snapshot.png
        				%>
        <div class="row" style="background-color: <%=cinza%>; border-radius: 8px; margin-left: 0; margin-right: 0; margin-top: 0">
        <div style="width: 9%; min-height: 7vh; float:left; padding-top: 1vh; padding-left: 1vw;">
        <%=foto%>
        </div>
        <div style="width: 88%; float: left; font-family: 'Fira Sans', sans-serif; padding-top: 1vh; padding-left: 2ch;">
        <span style="font-family: 'Fira Sans', sans-serif; color: <%=cor1%>;"><%=alu_disc.primeiro%>&nbsp;<%=alu_disc.sobrenome%></span> · <span style="font-family: 'Fira Sans', sans-serif; color: grey;"><%=disc.dataehora.text("dd/MM/yyyy")%></span><br><span style=""><%=disc.comentario%></span>
        </div>
        </div>

        <hr style="border: solid 2px white;">
        <%
        			}
        			%>
        </ul>
        <%
        		}
        		%>
        </div>
        </div>
        <%
        	}
        	//FECHA O IF PAGINA == TEXTO
        	if(pag_str != "termos")
        	{
        		link_termos = "https://creator.zohopublic.com/marciliofilho/ubique/page-embed/UBWIKI/uOnXDu8aUhYfeQb96gNy6JYs85fg2YgspqZNgC9WYxMNh3mgemS3P5GyHKmsYTNSk1JxaQk4ukrhrOg3jhWPjVzn3nnUMJSSmN7Y?alu_str=" + alu_str + "&pag_str=termos";
        		if(pag_str == "indice")
        		{
        			%>
        <div style="user-select: none; margin-auto; font-family: Playfair Display, serif; font-weight: 900; color: <%=cor1%>;text-align: center;">UBWIKI</div>
        <%
        		}
        		%>
        <div id="footer">
        <span style="user-select: none; margin: 2vh 0 0 0; padding: 2vh 0 0 0; color: <%=cor1%>;">A Ubiwiki é uma ferramenta de uso público e gratuito. Todos os direitos são reservados <br>ao Grupo Ubique. Clique <a href='<%=link_termos%>' style="color: <%=azulado%>">aqui</a> para rever os termos e condições de uso da página.</span>
        </div>
        <%
        	}
        	%>
        </body>
        </html>
        <%

        }%>
			</script>

		</table>

		<!-- jquery CDN -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
		<!-- zcml engine -->
		<script src="zcml.min.js" type="text/javascript" charset="utf-8"></script>

	</body>
</html>
