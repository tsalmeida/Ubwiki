<!-- Include Quill stylesheet -->
<link href="//cdn.quilljs.com/1.0.0/quill.core.css" rel="stylesheet">

<!-- Create the toolbar container -->
<div id="toolbar">
  <button class="ql-bold">Bold</button>
  <button class="ql-italic">Italic</button>
</div>

<!-- Create the editor container -->
<div id="editor">
  <p>Hello World!</p>
</div>

<!-- Include the Quill library -->
<script src="//cdn.quilljs.com/1.0.0/quill.core.js"></script>

<!-- Initialize Quill editor -->
<script>
  var editor = new Quill('#editor', {
    modules: { toolbar: '#toolbar' },
    theme: 'bubble'
  });
</script>
