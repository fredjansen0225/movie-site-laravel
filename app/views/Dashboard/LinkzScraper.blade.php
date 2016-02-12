@extends('Main.Boilerplate')

@section('bodytag')
	<body id="dashboard">
@stop

@section('content')

	<section id="dash-container">

		@include('Dashboard.Partials.Sidebar')

<div style="height:100px;"></div>

<div style="width:90%;margin:0 0 0 120px">

	<div class="tab-content">
		<div class="tab-pane active" id="scraper-log">
			<div style="text-align: center;">

				<div class="well2">
					<h2 class="queue_count">0</h2>
					<p>Titles in ZMovie Scraper Queue</p>
				</div>
				<div class="well2">
					<h2 class="day_scrape_count">0</h2>
					<p>Titles ZMovie Scraped in Past 24 hrs</p>
				</div>
				<div class="well2">
					<h2 class="total_scraped_count">0</h2>
					<p>Total Titles w/ Links</p>
				</div>
			</div>

			<br/>

			<br/>

			<table id="log" style="width:100%;border-color:#CCCCCC;border-spacing: 1px;border-collapse: separate;" border="1">
				<tr class="header">
					<th style="text-align:left;background:#CCC;">
						Log ID
					</th>
					<th style="text-align:left;background:#CCC;">
						Title
					</th>
					<th style="text-align:left;background:#CCC;">
						Action
					</th>
					<th style="text-align:left;background:#CCC;">
						Date Time
					</th>
				</tr>
				<tr data-imdb-scrape-log-id="0">

				</tr>
			</table>
		</div>
		<div class="tab-pane" id="titles">
			<h4 class="pull-left">All Title Links</h4>
			<div style="clear:both;"></div>
			<br/>
			<table  style="width:100%;border-color:#CCCCCC;border-spacing: 1px;border-collapse: separate;" border="1">
				<thead>
					<tr class="header">
						<th style="text-align:left;background:#CCC;">
							Title ID
						</th>
						<th style="text-align:left;background:#CCC;">
							Name
						</th>
						<th style="text-align:left;background:#CCC;">
							Type
						</th>
						<th style="text-align:left;background:#CCC;">
							Active Links
						</th>
						<th style="text-align:left;background:#CCC;">
							Scraped Links
						</th>
						<th style="text-align:left;background:#CCC;">
							Actions
						</th>
					</tr>
				</thead>

				<tbody>
				</tbody>
				<tfoot>
					<tr>
						<td class="load-more" colspan="7" style="text-align:center;">
							<a href="#" class="btn prev" onclick="loadMore(this);return false;" data-url="/linkz-scraper/titles" data-page="0">Prev</a> | <a href="#" class="btn next" onclick="loadMore(this);return false;" data-url="/linkz-scraper/titles" data-page="1">Next</a>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
		<div class="tab-pane" id="titles-without-links">
			<h4 class="pull-left"><span class="count">0</span> Titles Without Links</h4>
			<div style="clear:both;"></div>
			<br/>
			<table  style="width:100%;border-color:#CCCCCC;border-spacing: 1px;border-collapse: separate;" border="1">
				<thead>
					<tr class="header">
						<th style="text-align:left;background:#CCC;">
							Title ID
						</th>
						<th style="text-align:left;background:#CCC;">
							Name
						</th>
						<th style="text-align:left;background:#CCC;">
							Type
						</th>
						<th style="text-align:left;background:#CCC;">
							Active Links
						</th>
						<th style="text-align:left;background:#CCC;">
							Scraped Links
						</th>
						<th style="text-align:left;background:#CCC;">
							Actions
						</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
				<tfoot>
					<tr>
						<td class="load-more" colspan="7" style="text-align:center;">
							<a href="#" class="btn prev" onclick="loadMore(this);return false;" data-url="/linkz-scraper/titles-without-links" data-page="0">Prev</a> | <a href="#" class="btn next" onclick="loadMore(this);return false;" data-url="/linkz-scraper/titles-without-links" data-page="1">Next</a>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
		<div class="tab-pane" id="bad-links">
			<h4 class="pull-left"><span class="count">0</span> Bad Links</h4>
			<a class="btn btn-primary pull-right" onclick="deleteAllBadLinks();return false;" class="primary">Hide/Delete All Bad Links</a>
			<div style="clear:both;"></div>
			<br/>
			<table style="width:100%;border-color:#CCCCCC;border-spacing: 1px;border-collapse: separate;" border="1">
				<thead>
					<tr class="header">
						<th style="text-align:left;background:#CCC;">
							Title ID
						</th>
						<th style="text-align:left;background:#CCC;">
							Name
						</th>
						<th style="text-align:left;background:#CCC;">
							Type
						</th>
						<th style="text-align:left;background:#CCC;">
							S&amp;E
						</th>
						<th style="text-align:left;background:#CCC;">
							Active Links
						</th>
						<th style="text-align:left;background:#CCC;">
							Scraped Links
						</th>
						<th style="text-align:left;background:#CCC;">
							Actions
						</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
				<tfoot>
					<tr>
						<td class="load-more" colspan="7" style="text-align:center;">
							<a href="#" class="btn prev" onclick="loadMore(this);return false;" data-url="/linkz-scraper/bad-links" data-page="0">Prev</a> | <a href="#" class="btn next" onclick="loadMore(this);return false;" data-url="/linkz-scraper/bad-links" data-page="1">Next</a>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
	
</div>
</section>

<script type="text/javascript">
	var ready = (function(){    

	    var readyList,
	        DOMContentLoaded,
	        class2type = {};
	        class2type["[object Boolean]"] = "boolean";
	        class2type["[object Number]"] = "number";
	        class2type["[object String]"] = "string";
	        class2type["[object Function]"] = "function";
	        class2type["[object Array]"] = "array";
	        class2type["[object Date]"] = "date";
	        class2type["[object RegExp]"] = "regexp";
	        class2type["[object Object]"] = "object";

	    var ReadyObj = {
	        // Is the DOM ready to be used? Set to true once it occurs.
	        isReady: false,
	        // A counter to track how many items to wait for before
	        // the ready event fires. See #6781
	        readyWait: 1,
	        // Hold (or release) the ready event
	        holdReady: function( hold ) {
	            if ( hold ) {
	                ReadyObj.readyWait++;
	            } else {
	                ReadyObj.ready( true );
	            }
	        },
	        // Handle when the DOM is ready
	        ready: function( wait ) {
	            // Either a released hold or an DOMready/load event and not yet ready
	            if ( (wait === true && !--ReadyObj.readyWait) || (wait !== true && !ReadyObj.isReady) ) {
	                // Make sure body exists, at least, in case IE gets a little overzealous (ticket #5443).
	                if ( !document.body ) {
	                    return setTimeout( ReadyObj.ready, 1 );
	                }

	                // Remember that the DOM is ready
	                ReadyObj.isReady = true;
	                // If a normal DOM Ready event fired, decrement, and wait if need be
	                if ( wait !== true && --ReadyObj.readyWait > 0 ) {
	                    return;
	                }
	                // If there are functions bound, to execute
	                readyList.resolveWith( document, [ ReadyObj ] );

	                // Trigger any bound ready events
	                //if ( ReadyObj.fn.trigger ) {
	                //  ReadyObj( document ).trigger( "ready" ).unbind( "ready" );
	                //}
	            }
	        },
	        bindReady: function() {
	            if ( readyList ) {
	                return;
	            }
	            readyList = ReadyObj._Deferred();

	            // Catch cases where $(document).ready() is called after the
	            // browser event has already occurred.
	            if ( document.readyState === "complete" ) {
	                // Handle it asynchronously to allow scripts the opportunity to delay ready
	                return setTimeout( ReadyObj.ready, 1 );
	            }

	            // Mozilla, Opera and webkit nightlies currently support this event
	            if ( document.addEventListener ) {
	                // Use the handy event callback
	                document.addEventListener( "DOMContentLoaded", DOMContentLoaded, false );
	                // A fallback to window.onload, that will always work
	                window.addEventListener( "load", ReadyObj.ready, false );

	            // If IE event model is used
	            } else if ( document.attachEvent ) {
	                // ensure firing before onload,
	                // maybe late but safe also for iframes
	                document.attachEvent( "onreadystatechange", DOMContentLoaded );

	                // A fallback to window.onload, that will always work
	                window.attachEvent( "onload", ReadyObj.ready );

	                // If IE and not a frame
	                // continually check to see if the document is ready
	                var toplevel = false;

	                try {
	                    toplevel = window.frameElement == null;
	                } catch(e) {}

	                if ( document.documentElement.doScroll && toplevel ) {
	                    doScrollCheck();
	                }
	            }
	        },
	        _Deferred: function() {
	            var // callbacks list
	                callbacks = [],
	                // stored [ context , args ]
	                fired,
	                // to avoid firing when already doing so
	                firing,
	                // flag to know if the deferred has been cancelled
	                cancelled,
	                // the deferred itself
	                deferred  = {

	                    // done( f1, f2, ...)
	                    done: function() {
	                        if ( !cancelled ) {
	                            var args = arguments,
	                                i,
	                                length,
	                                elem,
	                                type,
	                                _fired;
	                            if ( fired ) {
	                                _fired = fired;
	                                fired = 0;
	                            }
	                            for ( i = 0, length = args.length; i < length; i++ ) {
	                                elem = args[ i ];
	                                type = ReadyObj.type( elem );
	                                if ( type === "array" ) {
	                                    deferred.done.apply( deferred, elem );
	                                } else if ( type === "function" ) {
	                                    callbacks.push( elem );
	                                }
	                            }
	                            if ( _fired ) {
	                                deferred.resolveWith( _fired[ 0 ], _fired[ 1 ] );
	                            }
	                        }
	                        return this;
	                    },

	                    // resolve with given context and args
	                    resolveWith: function( context, args ) {
	                        if ( !cancelled && !fired && !firing ) {
	                            // make sure args are available (#8421)
	                            args = args || [];
	                            firing = 1;
	                            try {
	                                while( callbacks[ 0 ] ) {
	                                    callbacks.shift().apply( context, args );//shifts a callback, and applies it to document
	                                }
	                            }
	                            finally {
	                                fired = [ context, args ];
	                                firing = 0;
	                            }
	                        }
	                        return this;
	                    },

	                    // resolve with this as context and given arguments
	                    resolve: function() {
	                        deferred.resolveWith( this, arguments );
	                        return this;
	                    },

	                    // Has this deferred been resolved?
	                    isResolved: function() {
	                        return !!( firing || fired );
	                    },

	                    // Cancel
	                    cancel: function() {
	                        cancelled = 1;
	                        callbacks = [];
	                        return this;
	                    }
	                };

	            return deferred;
	        },
	        type: function( obj ) {
	            return obj == null ?
	                String( obj ) :
	                class2type[ Object.prototype.toString.call(obj) ] || "object";
	        }
	    }
	    // The DOM ready check for Internet Explorer
	    function doScrollCheck() {
	        if ( ReadyObj.isReady ) {
	            return;
	        }

	        try {
	            // If IE is used, use the trick by Diego Perini
	            // http://javascript.nwbox.com/IEContentLoaded/
	            document.documentElement.doScroll("left");
	        } catch(e) {
	            setTimeout( doScrollCheck, 1 );
	            return;
	        }

	        // and execute any waiting functions
	        ReadyObj.ready();
	    }
	    // Cleanup functions for the document ready method
	    if ( document.addEventListener ) {
	        DOMContentLoaded = function() {
	            document.removeEventListener( "DOMContentLoaded", DOMContentLoaded, false );
	            ReadyObj.ready();
	        };

	    } else if ( document.attachEvent ) {
	        DOMContentLoaded = function() {
	            // Make sure body exists, at least, in case IE gets a little overzealous (ticket #5443).
	            if ( document.readyState === "complete" ) {
	                document.detachEvent( "onreadystatechange", DOMContentLoaded );
	                ReadyObj.ready();
	            }
	        };
	    }
	    function ready( fn ) {
	        // Attach the listeners
	        ReadyObj.bindReady();

	        var type = ReadyObj.type( fn );

	        // Add the callback
	        readyList.done( fn );//readyList is result of _Deferred()
	    }
	    return ready;
	})();

	
</script>
<script type="text/javascript">

	ready(function() {
		$('#subtabs a').click(function (e) {
			e.preventDefault();
			$(this).closest('ul').find('li').removeClass('active');
			$(this).closest('li').addClass('active');

			var id = $(this).attr('href');
			$('div.tab-pane').hide();
			$(id).show();
		});

		updateScraper();

		$('.next').click();
	});

	function insertLogs(logs)
	{
		$.each(logs, function(i, log) {
			var html = '<tr class="log new" style="display:none;" data-imdb-scrape-log-id="'+(log.id)+'">';
			var html = html + '<td>' + log.id_label + '</td>';
			var html = html + '<td>' + log.title + '</td>';
			var html = html + '<td>' + log.message + '</td>';
			var html = html + '<td>' + log.date + '</td>';
			var html = html + '</tr>';

			$('#log tr.header').after(html);
		});
		$('tr.new').fadeIn();
		$('tr.new').removeClass('new');
	}

	function updateScraper()
	{
		lastLogId = parseInt($('#log tbody tr.log:first').data('imdb-scrape-log-id'));
		if(!lastLogId)
		{
			lastLogId = 0;
		}

		$.post('/linkz-scraper/log', { lastLogId: lastLogId }, function(json) {

			$('h2.queue_count').html(json.queue_count);
			$('h2.day_scrape_count').html(json.day_scrape_count);
			$('h2.total_scraped_count').html(json.total_scraped_count)
			$('span.page-count').html(json.page_count);
			$('tr.log:gt(25)').remove();
			insertLogs(json.logs);

			setTimeout(updateScraper, 1000);

		});

	}

	function loadMore(button) {
		var pageToLoad = parseInt($(button).data('page'));
		if(pageToLoad < 0) {
			return;
		}

		console.log('Load more: '+pageToLoad);

		var url = $(button).data('url');

		$.post(url, {page: pageToLoad}, function(response) {
			
			if(response.total_count)
			{
				$(button).closest('div.tab-pane').find('span.count').html(response.total_count);
			}

			renderTable($(button).closest('table'), response.data);
			$(button).closest('table').find('a.next').data('page', pageToLoad+1);
			$(button).closest('table').find('a.prev').data('page', pageToLoad-1);
		});
	}

	function td(contents)
	{
		return '<td>'+contents+'</td>';
	}


	function renderTable(table, jsonData)
	{
		var html = '';
		switch(table.closest('div.tab-pane').attr('id'))
		{
			case 'titles':
				$.each(jsonData, function(i,el) {
					html += '<tr data-title-id="'+el.title_id+'">';
					html += td(el.title_id);
					html += td(el.title_name);
					html += td(el.title_type);
					html += td(el.active_link_count);
					html += td(el.total_link_count);
					html += td('<button class="btn btn-primary" onclick="rescrapeLinks(this);return false;">Re-scrape Links</button>')
					html += '</tr>';
				});
				console.log(html);
				table.find('tbody').html(html);
				break;
			case 'titles-without-links':
				$.each(jsonData, function(i,el) {
					html += '<tr data-title-id="'+el.title_id+'">';
					html += td(el.title_id);
					html += td(el.title_name);
					html += td(el.title_type);
					html += td(el.active_link_count);
					html += td(el.total_link_count);
					html += td('<button class="btn btn-primary" onclick="rescrapeLinks(this);return false;">Re-scrape Links</button>')
					html += '</tr>';
				});
				table.find('tbody').html(html);
				break;
			case 'bad-links':
				$.each(jsonData, function(i,el) {
					html += '<tr data-link-id="'+el.link_id+'">';
					html += td(el.link_id);
					html += td(el.link_url);
					html += td(el.title.id);
					html += td(el.title.title);
					html += td('+'+el.upvote_count+' | -'+el.downvote_count);
					html += td('<button class="btn btn-primary" onclick="deleteLink(this);return false;">Hide/Delete</button>')
					html += '</tr>';
				});
				table.find('tbody').html(html);
				break;
			default:
				break;
		}
	}

	function rescrapeAll(button)
	{
		var originalContent = $(button).html();
		
		$(button).html('Scheduling scrapes...');

		$.post('/linkz-scraper/scrape', function(response) {
			$(button).html(originalContent);
		});

	}

	function rescrapeLinks(button)
	{
		
		var originalContent = $(button).html();
		$(button).html('Scraping...');

		$.post('/linkz-scraper/scrape', { titleId: $(button).closest('tr').attr('data-title-id') }, function(response) {
			$(button).html(originalContent);
		});

	}

</script>


<style type="text/css">
	td, th {
		padding:10px !important;
		border-bottom:1px solid #CCC;
	}
	div.well2 {
		width:25%;
		display: inline-block;
		padding:20px 0px;
		margin:30px 25px;
		border:1px solid #CCC;
		background:#EEEEEE;
		text-align:center;
	}
</style>


<script type="text/javascript">
	var ready = (function(){    

	    var readyList,
	        DOMContentLoaded,
	        class2type = {};
	        class2type["[object Boolean]"] = "boolean";
	        class2type["[object Number]"] = "number";
	        class2type["[object String]"] = "string";
	        class2type["[object Function]"] = "function";
	        class2type["[object Array]"] = "array";
	        class2type["[object Date]"] = "date";
	        class2type["[object RegExp]"] = "regexp";
	        class2type["[object Object]"] = "object";

	    var ReadyObj = {
	        // Is the DOM ready to be used? Set to true once it occurs.
	        isReady: false,
	        // A counter to track how many items to wait for before
	        // the ready event fires. See #6781
	        readyWait: 1,
	        // Hold (or release) the ready event
	        holdReady: function( hold ) {
	            if ( hold ) {
	                ReadyObj.readyWait++;
	            } else {
	                ReadyObj.ready( true );
	            }
	        },
	        // Handle when the DOM is ready
	        ready: function( wait ) {
	            // Either a released hold or an DOMready/load event and not yet ready
	            if ( (wait === true && !--ReadyObj.readyWait) || (wait !== true && !ReadyObj.isReady) ) {
	                // Make sure body exists, at least, in case IE gets a little overzealous (ticket #5443).
	                if ( !document.body ) {
	                    return setTimeout( ReadyObj.ready, 1 );
	                }

	                // Remember that the DOM is ready
	                ReadyObj.isReady = true;
	                // If a normal DOM Ready event fired, decrement, and wait if need be
	                if ( wait !== true && --ReadyObj.readyWait > 0 ) {
	                    return;
	                }
	                // If there are functions bound, to execute
	                readyList.resolveWith( document, [ ReadyObj ] );

	                // Trigger any bound ready events
	                //if ( ReadyObj.fn.trigger ) {
	                //  ReadyObj( document ).trigger( "ready" ).unbind( "ready" );
	                //}
	            }
	        },
	        bindReady: function() {
	            if ( readyList ) {
	                return;
	            }
	            readyList = ReadyObj._Deferred();

	            // Catch cases where $(document).ready() is called after the
	            // browser event has already occurred.
	            if ( document.readyState === "complete" ) {
	                // Handle it asynchronously to allow scripts the opportunity to delay ready
	                return setTimeout( ReadyObj.ready, 1 );
	            }

	            // Mozilla, Opera and webkit nightlies currently support this event
	            if ( document.addEventListener ) {
	                // Use the handy event callback
	                document.addEventListener( "DOMContentLoaded", DOMContentLoaded, false );
	                // A fallback to window.onload, that will always work
	                window.addEventListener( "load", ReadyObj.ready, false );

	            // If IE event model is used
	            } else if ( document.attachEvent ) {
	                // ensure firing before onload,
	                // maybe late but safe also for iframes
	                document.attachEvent( "onreadystatechange", DOMContentLoaded );

	                // A fallback to window.onload, that will always work
	                window.attachEvent( "onload", ReadyObj.ready );

	                // If IE and not a frame
	                // continually check to see if the document is ready
	                var toplevel = false;

	                try {
	                    toplevel = window.frameElement == null;
	                } catch(e) {}

	                if ( document.documentElement.doScroll && toplevel ) {
	                    doScrollCheck();
	                }
	            }
	        },
	        _Deferred: function() {
	            var // callbacks list
	                callbacks = [],
	                // stored [ context , args ]
	                fired,
	                // to avoid firing when already doing so
	                firing,
	                // flag to know if the deferred has been cancelled
	                cancelled,
	                // the deferred itself
	                deferred  = {

	                    // done( f1, f2, ...)
	                    done: function() {
	                        if ( !cancelled ) {
	                            var args = arguments,
	                                i,
	                                length,
	                                elem,
	                                type,
	                                _fired;
	                            if ( fired ) {
	                                _fired = fired;
	                                fired = 0;
	                            }
	                            for ( i = 0, length = args.length; i < length; i++ ) {
	                                elem = args[ i ];
	                                type = ReadyObj.type( elem );
	                                if ( type === "array" ) {
	                                    deferred.done.apply( deferred, elem );
	                                } else if ( type === "function" ) {
	                                    callbacks.push( elem );
	                                }
	                            }
	                            if ( _fired ) {
	                                deferred.resolveWith( _fired[ 0 ], _fired[ 1 ] );
	                            }
	                        }
	                        return this;
	                    },

	                    // resolve with given context and args
	                    resolveWith: function( context, args ) {
	                        if ( !cancelled && !fired && !firing ) {
	                            // make sure args are available (#8421)
	                            args = args || [];
	                            firing = 1;
	                            try {
	                                while( callbacks[ 0 ] ) {
	                                    callbacks.shift().apply( context, args );//shifts a callback, and applies it to document
	                                }
	                            }
	                            finally {
	                                fired = [ context, args ];
	                                firing = 0;
	                            }
	                        }
	                        return this;
	                    },

	                    // resolve with this as context and given arguments
	                    resolve: function() {
	                        deferred.resolveWith( this, arguments );
	                        return this;
	                    },

	                    // Has this deferred been resolved?
	                    isResolved: function() {
	                        return !!( firing || fired );
	                    },

	                    // Cancel
	                    cancel: function() {
	                        cancelled = 1;
	                        callbacks = [];
	                        return this;
	                    }
	                };

	            return deferred;
	        },
	        type: function( obj ) {
	            return obj == null ?
	                String( obj ) :
	                class2type[ Object.prototype.toString.call(obj) ] || "object";
	        }
	    }
	    // The DOM ready check for Internet Explorer
	    function doScrollCheck() {
	        if ( ReadyObj.isReady ) {
	            return;
	        }

	        try {
	            // If IE is used, use the trick by Diego Perini
	            // http://javascript.nwbox.com/IEContentLoaded/
	            document.documentElement.doScroll("left");
	        } catch(e) {
	            setTimeout( doScrollCheck, 1 );
	            return;
	        }

	        // and execute any waiting functions
	        ReadyObj.ready();
	    }
	    // Cleanup functions for the document ready method
	    if ( document.addEventListener ) {
	        DOMContentLoaded = function() {
	            document.removeEventListener( "DOMContentLoaded", DOMContentLoaded, false );
	            ReadyObj.ready();
	        };

	    } else if ( document.attachEvent ) {
	        DOMContentLoaded = function() {
	            // Make sure body exists, at least, in case IE gets a little overzealous (ticket #5443).
	            if ( document.readyState === "complete" ) {
	                document.detachEvent( "onreadystatechange", DOMContentLoaded );
	                ReadyObj.ready();
	            }
	        };
	    }
	    function ready( fn ) {
	        // Attach the listeners
	        ReadyObj.bindReady();

	        var type = ReadyObj.type( fn );

	        // Add the callback
	        readyList.done( fn );//readyList is result of _Deferred()
	    }
	    return ready;
	})();

	ready(function() {
		$('#subtabs a').click(function (e) {
			e.preventDefault();
			
		})
	});
</script>


