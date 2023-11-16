<?php
    //The idea is to join into one thing:
    //A memory palace, a travelogue-like diary of impressions on art, a World Geography Course-like idea of the world, Charles Murray's Human Accomplishments
    //All using a structure connected with Wikipedia, iMDB etc.
    //As you add things to your collection, you also create a memory palace of items that can be joined into symbols of your creation.
    //So it is at the same time all these things and also a giant course on anything at all, focused on getting you to memorize whatever you want to memorize.
    //And the automatic test-taking of Marcílio's system
    //So how does it work? Each item is tagged in space and time: it happened in a time and it happened in a place or region etc. So Marco Polo would be tagged Italy, China etc, but also 13th century.
    //THE TAGGING IS DONE BY THE AI. You tell the AI something you're interested in, identify it by the Wikipedia page, the AI tags it.
    //The result is a memory palace structure you can access by geography or history. You ask the AI: write a text about every element I've added that is connected to Italy.
    //And the AI writes a text just for you, mentioning all the Italian things you have ever been interested on, with suggestions of other things that might interest you about Italy.
    //Like on Ubwiki, you're encouraged to write about it, take tests about it, everything is recorded and fed into the AI again, to generate another and another version of your memory palace artifact.
    //The AI can generate images as well, of course, a series of them per memory palace object, each a synthesis of the knowledge you're seeking to memorize.
    //You ask the AI to tell you about the 13th century, but all your references do far are European. You ask him to add Middle-Eastern elements and so it goes. It's doable.
	include "engine.php";
	if (!isset($_SESSION['user_id'])) {
		print('Not logged in.');
		exit();
	}
	$pagina_tipo = 'cosmography';
	$pagina_id = $_SESSION['user_escritorio'];
	$pagina_title = 'Cosmography';
	$pagina_favicon = 'cosmography.ico';
	if ($_POST) {
		header("Location: " . $_SERVER['REQUEST_URI']);
		exit();
	}
	include 'templates/html_head.php';
?>
<body class="bg-dark">
<style>
    html {
        font-size: 1rem !important;
    }
</style>
<div class="container-fluid">
    <div class="row sticky-top">
        <div id="cosmography_container" class="col-12">
            <h1 class="text-light">Cosmography</h1>
            <p class="text-light">A personalized encyclopedia, a diary of impressions, a record of what you value and admire, an AI-powered memory palace, the future of learning.</p>
        </div>
    </div>
</div>