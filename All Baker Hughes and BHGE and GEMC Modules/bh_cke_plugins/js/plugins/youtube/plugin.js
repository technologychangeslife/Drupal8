(function($) {
    CKEDITOR.plugins.add('youtube', {
        init: function( editor )
        {
            editor.addCommand('youtube', new CKEDITOR.dialogCommand('youtube', {
				allowedContent: 'div{*}(*); iframe{*}[!width,!height,!src,!frameborder,!allowfullscreen,!allow]; object param[*]; a[*]; img[*]'
            }));

            editor.ui.addButton('Youtube', {
				label : 'Youtube Video',
				toolbar : 'insert',
				command : 'youtube',
				icon : this.path + 'images/icon.png'
            });
            
            CKEDITOR.dialog.add('youtube', function (instance) {
                var video;
                var video_invalid_error = 'Please enter valid youtube video url';
                return {
					title : 'Add Youtube Video',
					minWidth : 550,
					minHeight : 120,
					contents :[{
                        id : 'youtubeForm',
                        expand : true,
                        elements :
                            [
                                {
                                    id : 'txtTitle',
                                    type : 'text',
                                    label : 'Add Youtube Title:',
                                    labelLayout: 'horizontal',
                                    onLoad : function () {
                                        //console.log(this);
                                      }
                                },
                                {
                                    id : 'txtUrl',
                                    type : 'text',
                                    label : 'Add Youtube URL:',
                                    labelLayout: 'horizontal',
                                    required: true,
                                    validate : function () {
                                        if (!this.getValue()) {
                                            alert(video_invalid_error);
                                            return false;
                                        }
                                        else{
                                            video = youtubeVideoId(this.getValue());

                                            if (this.getValue().length === 0 ||  video === false)
                                            {
                                                alert(video_invalid_error);
                                                return false;
                                            }
                                        }
                                    }
                                },
                                /*{
                                    id : 'chkAutoplay',
                                    type : 'checkbox',
                                    'default' : false,
                                    label : 'Autoplay'
                                },
                                {
                                    id : 'chkControls',
                                    type : 'checkbox',
                                    'default' : true,
                                    label : 'Show Controls'
                                }*/
                            ],
                        }],
                        onOk: function()
                        {
                            var content = '';
                            var content_title = '';
                            var url = 'https://www.youtube.com/embed/', options = [],  autoplayOption='',videoTitle='';
							url += video;
                            options.push('rel=0');
                            
							/*if (this.getContentElement('youtubeForm', 'chkAutoplay').getValue() === true) {
								options.push('autoplay=1');
								autoplayOption='autoplay';
							}

							if (this.getContentElement('youtubeForm', 'chkControls').getValue() === false) {
								options.push('controls=0');
                            }*/
                            
                            
							if (options.length > 0) {
								url = url + '?' + options.join('&');
                            }
                            
                            content = '<div class="bh-cke-youtube-video"><div class="bh-cke-youtube-video__container"><div class="bh-cke-youtube-video__content-container"><div class="bh-cke-youtube-video__content"><div class="bh-cke-youtube-video__content-wrapper">';
                            content += '<div class="video-content"><iframe ' + (autoplayOption ? 'allow="' + autoplayOption + ';" ' : '') + ' src="' + url + '" ';
                            content += 'frameborder="0" allowfullscreen></iframe></div>';


                            if (this.getContentElement('youtubeForm', 'txtTitle').getValue().length > 0) {
								videoTitle = this.getContentElement('youtubeForm', 'txtTitle').getValue();
                                content_title = '<div class="cke-youtube-video-title">'+videoTitle+'</div>';
                            }

                            content += '</div></div></div></div>'+content_title+'</div>';
                            var element = CKEDITOR.dom.element.createFromHtml(content);
						    var instance = this.getParentEditor();
                            instance.insertElement(element);
                        }
                    }
                });
            }
        });
})(jQuery);

function youtubeVideoId(url) {
	var p = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
	return (url.match(p)) ? RegExp.$1 : false;
}