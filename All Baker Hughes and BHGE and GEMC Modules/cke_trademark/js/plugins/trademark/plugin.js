(function($) {
    CKEDITOR.plugins.add( 'trademarkdagger', {
     init: function( editor )
     {
      editor.addCommand( 'trademark', {
       exec : function( editor ) {
        //here is where we tell CKEditor what to do.
        editor.insertHtml('&dagger;');
       }
      });
      
      editor.ui.addButton( 'Trademark', {
       label: 'Add Trademark Dagger', //this is the tooltip text for the button
       command: 'trademark',
       icon: this.path + 'images/icon.png'
      });
     }
    });
   })(jQuery);