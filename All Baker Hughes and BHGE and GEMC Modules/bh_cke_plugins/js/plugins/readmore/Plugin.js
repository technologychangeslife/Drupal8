(function ($) {
CKEDITOR.plugins.add('readmore', {
  icons: "readmore",
  init: function(editor) {
    //Adding readmore icon in ckeditor
    editor.ui.addButton('Readmore', {
      label: "Insert readmore",
      command: "cke-readmore",  
      toolbar: "insert",
      icon: this.path + 'icon/readmore.png'
    });
    var cssPath = this.path + 'readmore.css';
    editor.on('mode', function () {
      if (editor.mode === 'wysiwyg') {
        this.document.appendStyleSheet(cssPath);
      }
    }); 
    var allowedContent = 'div(!cke-readmore)';
    // Wrap selection.
    editor.addCommand('cke-readmore', {
      allowedContent: allowedContent,
      exec: function (editor) {

        var selectedHtml = '';
        var selection = editor.getSelection();

        if (selection) {
          selectedHtml = getSelectionHtml(selection);
        }

        var div = new CKEDITOR.dom.element.createFromHtml(
        '<div class="ckeditor-readmore">' + selectedHtml + '</div>' );
        editor.insertElement(div);

        function getSelectionHtml(selection) {
          var ranges = selection.getRanges();
          var html = '';
          for (var i = 0; i < ranges.length; i++) {
            html += getRangeHtml(ranges[i]);
          }
          return html;
        }

        function getRangeHtml(range) {
          var content = range.extractContents();
          // `content.$` is an actual DocumentFragment object (not a CKEDitor abstract)
          var children = content.$.childNodes;
          var html = '';
          for (var i = 0; i < children.length; i++) {
            var child = children[i];
            if (typeof child.outerHTML === 'string') {
              html += child.outerHTML;
            }
            else {
              html += child.textContent;
            }
          }
          return html;
        }
      }
    }); 	
  }
})
})(jQuery);