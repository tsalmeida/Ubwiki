<html>


<head>
<!-- Include Quill stylesheet -->
<link href="https://cdn.quilljs.com/1.0.0/quill.snow.css" rel="stylesheet">

<style>
  .around_quill {
    max-width: 65ch;
    height: 60em;
  }
</style>

</head>

<body>

<!-- Create the toolbar container -->
<div class='around_quill'>
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
  var editor = new Quill('#editor', {
    modules: { toolbar: '#toolbar' },
    theme: 'snow'
  });
</script>

</html>
