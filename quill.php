<html>


<head>
<!-- Include Quill stylesheet -->
<link href="https://cdn.quilljs.com/1.0.0/quill.snow.css" rel="stylesheet">

<style>
  .ql-around {
    max-width: 65ch;
    height: 40em;
  }
</style>

</head>

<body>

<!-- Create the toolbar container -->
<div id='quill_cont' class='ql-around'>
  <div id="toolbar"></div>

  <!-- Create the editor container -->
  <div id="editor">
    <p>Hello World!</p>
  </div>
</div>
</body>

<!-- Include the Quill library -->
<script src="https://cdn.quilljs.com/1.0.0/quill.js"></script>

<!-- Initialize Quill editor -->
<script>

  var toolbarOptions = [
    ['italic'],        // toggled buttons
    ['blockquote', 'code-block'],
    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    [{ 'script': 'super' }],                            // superscript

    [{ 'header': [2, false] }],

    ['clean']                                         // remove formatting button
  ];

  var quill = new Quill('#editor', {
    modules: {
      toolbar: toolbarOptions,
      container: '#quill_cont'
    },
    theme: 'snow'
  });

</script>

</html>
