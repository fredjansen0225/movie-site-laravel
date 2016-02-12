(function($) {
    'use strict'

/**
 * Attach click handlers to videos images and play the video
 * in a modal on click.
 * 
 * @type {Object}
 */
ko.bindingHandlers.playVideos = {

    init: function (element, valueAccessor, allBindingsAccessor, context) {

        var modal = $('#vid-modal .modal-body');

        //dispose of video.js video player on bootstrap modal close
        $('#vid-modal').on('hidden.bs.modal', function (e) {
            if (vars.trailersPlayer == 'default') {
                modal.html('');
            } else {
                videojs('vidjs').dispose();
            }
        })

        $('.play').click(function(e) {
            e.stopPropagation();
            
            var source = $(this).data('source'),
                poster = $(this).data('poster');
            
            //if there's no source we'll just open new window
            //with given url
            if (vars.trailersPlayer == 'default') {
                modal.html('<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="'
                    +source+'?autoplay=1&iv_load_policy=3&modestbranding=1" wmode="opaque" allowfullscreen="true"></iframe></div>');
            } else {
                //create player element
                modal.html('<video id="vidjs" class="video-js vjs-default-skin" controls preload="auto" width="100%" height="500px" poster="'+poster+'"> </video>');
                

                //set up either to play from youtube or mp4 file
                if (source.indexOf('youtube') != -1) {
                    videojs('vidjs', { "techOrder": ["youtube"]}).src(source).play();
                }
                else {
                    videojs('vidjs', { "techOrder": ["html5", "flash"]}).src(source).play();
                }
            }

            $('#vid-modal').modal();
            
            return false;
       
        });

        return false;
    }
};

})(jQuery);