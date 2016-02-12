(function($) {
	'use strict'

    app.viewModels.titles.show = {

        /**
         * Whether or not user has added this title to watchlist.
         * 
         * @type boolean
         */
        watchlist: ko.observable(false),

        /**
         * Whether or not user has added this title to favorites.
         * 
         * @type boolean
         */
        favorite: ko.observable(false),

        /**
         * Episode for which we should rended stream links.
         * 
         * @type int
         */
        activeEpisode: ko.observable(1),

        handleLists: function(name) {
            var self = app.viewModels.titles.show,
                alreadyAdded = self[name]();

            app.utils.ajax({
                url: alreadyAdded ? vars.urls.baseUrl + '/lists/remove' : vars.urls.baseUrl + '/lists/add',
                data: ko.toJSON({ _token: vars.token, list_name: name, title_id: vars.titleId }),
                success: function(data) {
                    if (alreadyAdded) { 
                        self[name](false);
                    } else {
                        self[name](true);
                    }  
                }
            })
        },

        showEpisodeModal: function(id) {
            var self = this,
                id   = parseInt(id);

            if (vars.links[id][0]) {
                self.activeEpisode(id);
                self.renderTab(vars.links[id][0].id, vars.links[id][0].url, vars.links[id][0].type, 700)
            }

            $('#stream-modal').modal('toggle');
        },

        /**
         * Render stream tab contents on click with appropriate contents.
         * 
         * @param  int id  
         * @param  string url 
         * @param  string type
         * 
         * @return void    
         */
        renderTab: function(id, url, type, iframeHeight) {
            var $contents = $('#videos .tab-content'),
                height = iframeHeight ? iframeHeight : 500;

            //handle tab buttons active state
            $('#videos .nav-tabs > li').removeClass('active');
            $('#'+id).addClass('active');

            if ($('#vidjs').length > 0) {
                videojs('vidjs').dispose();
            }
            

            //if it's an embed simply inject the url into an iframe
            if (type == 'embed') {
                $contents.html('<iframe width="100%" height="'+height+'px" frameborder="0" src="'+url+'" scrolling="no"></iframe>');

            //if it's a video we'll play it using video.js
            } else if (type == 'video') {

                //inject base video markup
                $contents.html('<video id="vidjs" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" width="100%" height="'+height+'px"> </video>');

                //use appropriate tech to play video depending if it's a youtube url or html video
                if (url.indexOf('youtube') != -1) {
                    videojs('vidjs', { "techOrder": ["youtube"]}).src(url).play();
                } else {
                    videojs('vidjs', { "techOrder": ["html5", "flash"]}).src(url).play();
                }   
            } else {
                $contents.html('<a href="'+url+'">'+url+'</a>');
            }       
        },

        /**
         * Send request to server to report a broken link.
         * 
         * @param  int/string id
         * @return void
         */
        report: function(id) {
            
            app.utils.ajax({
                url: vars.urls.baseUrl+'/links/report',
                data: ko.toJSON({ _token: vars.token, link_id: id }),

                success: function(data) {
                    app.utils.noty(data, 'success');
                },
                error: function(data) {
                    app.utils.noty(data.responseJSON, 'error');
                }
            });
        },

        showTrailer: function() {
            var $mask = $('#trailer-mask');
            
            //set up either to play from youtube or mp4 file
            // if ($mask.data('src').indexOf('youtube') != -1) {
            //     videojs('trailer', { "techOrder": ["youtube"]}).src($mask.data('src')).play();
            // }
            // else {
            //     videojs('trailer', { "techOrder": ["html5", "flash"]}).src($mask.data('src')).play();
            // }
            

            $mask.css('display', 'none');
            $('#videos').css('display', 'block');

            //reposition social and lists buttons once video is shown
            $('#social').css('top', 0).css('left', 0);
            $('#lists').css('top', 0).css('right', 0);

            //show/hide social and list buttons when player controls are shown/hidden
            videojs('trailer').on('userinactive', function() {
                $('#social').css('display', 'none');
                $('#lists').css('display', 'none');
            });

            videojs('trailer').on('useractive', function() {
                $('#social').css('display', 'block');
                $('#lists').css('display', 'block');
            });
        },

        start: function(video) {
            var self = app.viewModels.titles.show;

            //render first link in first tab on page load
            if (video) {
                self.renderTab(video.id, video.url, video.type);
            }

            //see if user has already added this title to favorites or watchlist
            $.each(vars.lists, function(i,v) {
                if (v.title_id == vars.titleId && v.watchlist) {
                    self.watchlist(true);
                }

                if (v.title_id == vars.titleId && v.favorite) {
                    self.favorite(true);
                }
            });

            app.startGallery();

            var h = $('#details-container').height();
            $('#details-container .img-responsive').height(h);

            app.loadDisqus();
        },

        reviews: app.viewModels.reviews,
    }

})(jQuery);
