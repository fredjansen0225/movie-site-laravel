(function($) {
    'use strict'

    /**
     * Bind jquery raty plugin to the element.
     * 
     * @type {Object}
     */
    ko.bindingHandlers.raty = {
    	
        init: function(element, valueAccessor, allBindings, context) {
            
        	var rating = ko.utils.unwrapObservable(valueAccessor()),
                stars  = allBindings.get('stars'),
                $el    = $(element);
                
                if (stars > 5) {
                    var score = (''+Math.floor(rating)).length === 3 ? 10 : (''+rating)[0];
                } else {
                    var score = rating / 2;
                }
                

        	$el.raty({
    			path: vars.urls.baseUrl + '/assets/images',
    			width: 240,
    			readOnly: allBindings.get('readOnly'),
    			number: stars ? stars : 10,
                halfShow: true,
                hints: [null, null, null, null, null],
    			score: score,
                click: allBindings.get('clickCallback'),
                size: 10,
    		});

            ko.utils.domNodeDisposal.addDisposeCallback(element, function() {
                $el.raty('destroy');
            });    
        }
    };

    /**
     * Innitiates date picker on a form field.
     * 
     * @type {Object}
     */
    ko.bindingHandlers.picker = {
        init: function (element, valueAccessor, allBindingsAccessor, context) {
            new Pikaday({
                field: $(element)[0], 

                /**
                 * Format the date so we can use it to build a query
                 * with php serverside.
                 * 
                 * @param  Date d 
                 * @return void
                 */
                onSelect: function(d) {
                    var name  = valueAccessor(),
                        day   = d.getDate(),
                        month = d.getMonth(),
                        year  = d.getFullYear();

                    var full = year+'-'+(month+1)+'-'+day;

                    app.paginator.context.params[name](full);
                }
            }); 
        }
    };

    /**
     * Dynamically add more/less button to text.
     * 
     * @type {Object}
     */
    ko.bindingHandlers.moreLess = {
        
        init: function(element, valueAccessor, allBindings, context) {
                         
            var limit = valueAccessor() ? valueAccessor() : 200;
            
            $(document).off("click", '.morelink');
             
            $(document).on({click: function () {
     
                    var $this = $(this);
                    if ($this.hasClass('less')) {
                        $this.removeClass('less');
                        $this.html('more');
                    } else {
                        $this.addClass('less');
                        $this.html('less');
                    }
                    $this.parent().prev().toggle();
                    $this.prev().toggle();
                    return false;
                }
            }, '.morelink');
     
            return $(element).find('p').each(function () {
                var $this = $(this);
                if($this.hasClass("shortened")) return;
                 
                $this.addClass("shortened");
                var content = $this.html();
                if (content.length > limit) {
                    var c = content.substr(0, limit);
                    var h = content.substr(limit, content.length - limit);
                    var html = c + '<span class="moreellipses">...</span><span class="morecontent"><span>' + h + '</span> <a href="#" class="morelink">' + 'more' + '</a></span>';
                    $this.html(html);
                    $(".morecontent span").hide();
                }
            }); 
        }
    };

    /**
     * Hide/Show autocomplete container on blur/focus events.
     * 
     * @type {Object}
     */
    ko.bindingHandlers.hideOnBlur = {
        init: function (element, valueAccessor, allBindingsAccessor, context) {

            var $container = $('.autocomplete-container');

            $(element).focus(function() {
                if (context.autocompleteResults().length > 0) {
                    $container.fadeIn();
                }
            }).blur(function() {
                $container.fadeOut();
            });
        }
    };


})(jQuery);
