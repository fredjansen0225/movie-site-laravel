(function($) {
	'use strict'

	app.paginator = {

		/**
		 * Stores the total number of pages.
		 * 
		 * @type ko.observable(String),
		 */
		totalPages: ko.observable(1),

		/**
		 * How much games to display per page.
		 * 
		 * @type {Integer}
		 */
		perPage: ko.observable(15),

		/**
		 * Tracks current page the user is on.
		 * 
		 * @type ko.observable(Integer),
		 */
		currentPage: ko.observable(1),

		/**
		 * Indicates if we're currently in a progress
		 * of making an ajax call to the server.
		 * 
		 * @type Boolean
		 */
		loading: ko.observable(),

		/**
		 * Context the paginator is running in.
		 * 
		 * @type Object
		 */
		context: null,

		/**
		 * Delete item from context source items and database.
		 * 
		 * @param  Object item
		 * @return void
		 */
		deleteItem: function(item) {

			app.utils.ajax({
				url: vars.urls.baseUrl + '/' + app.paginator.context.uri + '/' + item.id,
				type: 'DELETE',
				data: ko.toJSON({ _token: vars.token }),
				success: function(data) {
					app.paginator.context.sourceItems.remove(item);
					app.utils.noty(data, 'success');
				},
				error: function(data) {
					app.utils.enablePage();
					app.utils.noty(data.responseJSON, 'error');
				}
			});
		},

		/**
		 * Move to the next page.
		 * 
		 * @return void
		 */
		nextPage: function() {
			var self = app.paginator;
			self.loading(true);

			self.currentPage(self.currentPage() + 1);
		},

		/**
		 * Move to the previous page.
		 * 
		 * @return void
		 */
		previousPage: function() {
			var self = app.paginator;
			self.loading(true);

			self.currentPage(self.currentPage() - 1);
		},

		/**
		 * Get any params set on context that we will need
		 * to send to server.
		 * 
		 * @return Object
		 */
		getParams: function() {
			var self   = this,
				params = { _token: vars.token, perPage: self.perPage() };

			params.page  = self.currentPage();

			if (self.context.params) {
				$.each(self.context.params, function(i,v) {
					params[i] = ko.utils.unwrapObservable(v);
				});
			}

			return params;
		},

		/**
		 * Do some bootstraping from paginator view model.
		 * 
		 * @param  context
		 * @return void
		 */
		start: function(context, element, perPage) {
			app.paginator.context = context;
	    	app.paginator.paginate();
	    	app.paginator.disablePageOnLoading();

	    	if (element) {
	    		ko.applyBindings(context, $(element)[0]);
	    	}

	    	if (perPage) {
	    		app.paginator.perPage(perPage);
	    	}
		},
	};

	/**
	 * Hit url provided by context to retrieve results for pagination.
	 * 
	 * @return void
	 */
	app.paginator.paginate = ko.computed(function() {
    	var self = this;

    	//if not triggered by next or previous set curent page to 1
    	self.loading(true);
    	
    	$.ajax(vars.urls.baseUrl + '/' + self.context.uri + '/paginate', {
	        type: 'GET',
	        data: self.getParams(),
	        dataType: 'json',
	        success: function(response)	{
	        	self.totalPages(Math.ceil(response.totalPages));
	  
	        	if (response.items.length > 0) {
	        		self.context.sourceItems(response.items);
	        	} else {
	        		self.context.sourceItems.removeAll();
	        		$('#paginate').append('<li><h2 style="text-align: center; margin-top: 30px">'+vars.trans.noResults+'</h2></li>');
	        	}
	        	
	        	self.loading(false);
	        }
	    });
	}, app.paginator, {deferEvaluation: true}).extend({ rateLimit: { method: "notifyWhenChangesStop", timeout: 400 } });

	/**
	 * Indicates whether or not we currently are on the last page.
	 * 
	 * @return boolean
	 */
	app.paginator.hasNext = ko.computed(function() {
		var self = this;

		if (self.totalPages() === 0) {
			return false;
		}

		return self.currentPage() != self.totalPages();

    }, app.paginator, { deferEvaluation: true });

    /**
	 * Indicates whether or not we currently are on the first page.
	 * 
	 * @return boolean
	 */
	app.paginator.hasPrevious = ko.computed(function() {
		var self = this;

		return self.currentPage() >= 2;

    }, app.paginator, { deferEvaluation: true });

   	/**
   	 * Overlay page with black background if we're
   	 * in progress of ajax request.
   	 * 
   	 * @return void
   	 */
	app.paginator.disablePageOnLoading = ko.computed(function() {
		var self = this;

		if (self.loading()) {
			app.utils.disablePage();
		} else {
			app.utils.enablePage();
		}

    }, app.paginator, { deferEvaluation: true });

})(jQuery);