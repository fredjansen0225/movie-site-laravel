(function($) {
	'use strict'

	app.viewModels.actors = {

		/**
		 * Holds all titles.
		 * 
		 * @type ko.observable(Array)
		 */
		sourceItems: ko.observableArray(),

		/**
		 * Any params paginator should filter or sort results on.
		 * 
		 * @type {Object}
		 */
		params: {

			/**
			 * Currently selected sorting option.
			 * 
			 * @type ko.observable(String)
			 */
			order: ko.observable(),

			/**
			 * Stores users query to filters games on.
			 * 
			 * @type ko.observable(String),
			 */
			query: ko.observable().extend({ rateLimit: { method: "notifyWhenChangesStop", timeout: 400 } }),
		},

		/**
		 * Uri to hit for paginated results.
		 * 
		 * @type {String}
		 */
		uri: 'people',
	};

})(jQuery);