<?php
	$pagina_tipo = 'repeats';
echo "
<header>
<h1>Duplicate Word Finder</h1>
<link rel='stylesheet' href='repeats.css'>
</header>
<main>
  <div id='actions'>
    <div>
      <button id='copy' class='pure-material-button-contained'>Copy</button>
      <button id='clear' class='pure-material-button-text'>Clear</button>
    </div>
    <label class='pure-material-switch'>
            <input id='spellcheck' type='checkbox' checked>
            <span>Spell Check</span>
        </label>
  </div>
  <div id='options'>
    <label class='pure-material-slider'>
      <input id='length' type='range' min='1' max='10' value='4'>
      <span>Minimum Word Length: <output>4</output></span>
    </label>
    <label class='pure-material-slider'>
      <input id='frequency' type='range' min='1' max='10' value='3'>
      <span>Minimum Frequency: <output>3</output></span>
    </label>
  </div>
  <hr>
  <div id='text'>
    <textarea>
Paste your text by pressing Ctrl+V or ⌘+V. (Once you click here, these instructions will be cleared for your convenience ;)

The slider on the top right can be used to adjust the minimum length of words to detect. The default setting is 4, so words like 'and', 'for', 'the', etc. won't pollute the analysis. The duplicate words can be found below the slider highlighted by various colors. The list is sorted by the number of occurrence starting with the highest. The highlighting for any individual word can be turned off by clicking on the word in the list.

You can edit your text here, duplicates are detected real time. When you're finished editing, you can copy or clear the result using the corresponding buttons. You can also turn off the spell checker if you don't need it.

~~~

I often find myself sending emails and messages consisting of two or three sentences. This is where I usually commit an unintentional word repetition, but only realize it after hitting send. This tool comes handy for such cases, and you're free to use it for your benefit as well! :)
</textarea>
    <div id='highlights'></div>
  </div>
  <ol id='list'></ol>
</main>
<footer>
  <div>
    Created by Bence Szabó (finnhvman)<br/>
    <br/>
    <a href='https://twitter.com/finnhvman' target='_top'>Twitter</a> &nbsp; | &nbsp;
    <a href='https://www.linkedin.com/in/finnhvman/' target='_top'>LinkedIn</a> &nbsp; | &nbsp;
    <a href='https://codepen.io/finnhvman/' target='_top'>CodePen</a>
  </div>
<a href='https://www.buymeacoffee.com/finnhvman' target='_blank'><img src='https://cdn.buymeacoffee.com/buttons/v2/default-red.png' alt='Buy Me A Coffee' style='height: 60px !important;width: 217px !important;' ></a>
  <div>
Created with<br/>
    <br/>
    <a href='https://codepen.io/collection/nZKBZe/' target='_top'>Pure CSS Material Components</a>
  </div>
</footer>
<template>
    <li class='item'>
        <label>
            <input class='hidden' type='checkbox'>
            <span class='count'></span>
            <span class='word'></span>
        </label>
    </li>
</template>
<script type='text/javascript' src='repeats.js'></script>
";
