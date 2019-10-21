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
<div class='ql-around'>
  <div id="toolbar">
    <button class="ql-bold">Bold</button>
    <button class="ql-italic">Italic</button>
  </div>

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

  var container = quill.addContainer('ql-around');
  var toolbarOptions = [
    ['italic', 'superscript', 'image', 'link', 'blockquote'],
    [{'header': 1}, {'header': 2}],
    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    [{ 'script': 'sub'}, { 'script': 'super' }],
    ['clean']
  ];

  var options = {
    debug: 'info',
    modules: {
      toolbar: {
        container: '#toolbar',
      }
      toolbar: toolbarOptions
    },
    theme: 'snow'
  }

  var editor = new Quill('#editor', options);

</script>

</html>
