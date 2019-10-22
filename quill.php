<!-- Include stylesheet -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<!-- Create the editor container -->
<div id="editor">
  <p>Hello World!</p>
  <p>Some initial <strong>bold</strong> text</p>
  <p><br></p>
</div>

<!-- Include the Quill library -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<!-- Initialize Quill editor -->
<script>

var toolbarOptions = [
  ['italic'],        // toggled buttons
  ['blockquote', 'code-block'],
  [{ 'list': 'ordered'}, { 'list': 'bullet' }],
  [{ 'script': 'super' }],
  ['image'],
  [{ 'header': [2, false] }],

  ['clean']
];

var formats_wl = [
  ['italic'], ['script'], ['blockquote'], [{'header': 2}, ['list'], ['image']]
]


  var quill = new Quill('#editor', {
    theme: 'snow'
  });
</script>
