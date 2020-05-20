(function($) {
    CKEDITOR.plugins.add('bh_blockquote', {
        init: function( editor )
        {
            var pluginDirectory = this.path;
            editor.addContentsCss(pluginDirectory + 'styles/bh_blockquote.css');

            editor.addCommand('bh_blockquote', new CKEDITOR.dialogCommand('bh_blockquote', {
				allowedContent: 'div{*}(*)'
            }));

            editor.ui.addButton('BhBlockquote', {
				label : 'BH BlockQuote',
				toolbar : 'insert',
				command : 'bh_blockquote',
				icon : this.path + 'images/icon.png'
            });
            
            CKEDITOR.dialog.add('bh_blockquote', function (instance) {
                return {
					title : 'Add BH Blockquote',
					minWidth : 550,
					minHeight : 120,
					contents :[{
                        id : 'bhBlockquoteForm',
                        expand : true,
                        elements :
                            [
                                {
                                    id : 'txtMessage',
                                    type : 'textarea',
                                    label : 'Quote Message:',
                                    labelLayout: 'horizontal',
                                    required: true,
                                    validate: function() {
                                        if (this.getValue().length < 5) {
                                            alert( 'Quote message is too short.');
                                            return false;
                                        }
                                    }
                                },
                                {
                                    id : 'txtAuthor',
                                    type : 'text',
                                    label : 'Author Name:',
                                    labelLayout: 'horizontal',
                                    required: true,
                                    validate : function () {
                                        if (!this.getValue()) {
                                            alert('Author name can not be empty');
                                            return false;
                                        }
                                    }
                                },
                                {
                                    id : 'txtAuthorUrl',
                                    type : 'text',
                                    label : 'Author Link:',
                                    labelLayout: 'horizontal',
                                    validate : function () {
                                        var urlVal = this.getValue();
                                        if (urlVal.length > 0 && !isValidUrl(urlVal)) {
                                            alert('Author link should be a valid url');
                                            return false;
                                        }
                                    }
                                },
                                {
                                    id : 'txtCaption',
                                    type : 'text',
                                    label : 'Caption:',
                                    labelLayout: 'horizontal'
                                }
                            ],
                        }],
                        onOk: function()
                        {
                            var message = this.getContentElement('bhBlockquoteForm', 'txtMessage').getValue();
                            var author = this.getContentElement('bhBlockquoteForm', 'txtAuthor').getValue();
                            var authorlink = this.getContentElement('bhBlockquoteForm', 'txtAuthorUrl').getValue();
                            var caption = this.getContentElement('bhBlockquoteForm', 'txtCaption').getValue();

                            var link = (authorlink.length > 0) ? authorlink : '';
                            var content = '<div class="bh-cke-blockquote"><blockquote>';
                            content +='<p class="bh-cke-blockquote-text">'+ message +'</p>';
                            content +='<div class="bh-cke-blockqoute-contact"><p>';
                            if(link == ''){
                              content +='<span class="bh-cke-blockqoute-contact-no-link">'+ author +'</span>, ';
                            }else{
                              content +='<a href="'+ link +'" target="_blank">'+ author +'</a>, ';
                            }

                            if(caption.length > 0)
                            content += caption;
                            
                            content +='</p></div></blockquote></div>';

                            var element = CKEDITOR.dom.element.createFromHtml(content);
						    var instance = this.getParentEditor();
                            instance.insertElement(element);
                        }
                    }
                });
            }
        });
})(jQuery);

function isValidUrl(url) {
	var urlregex = /^(https?|ftp):\/\/([a-zA-Z0-9.-]+(:[a-zA-Z0-9.&%$-]+)*@)*((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])){3}|([a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(:[0-9]+)*(\/($|[a-zA-Z0-9.,?'\\+&%$#=~_-]+))*$/;
    return urlregex.test(url);
}