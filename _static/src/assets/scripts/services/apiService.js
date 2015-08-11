/**
 * A service for handling API requests
 *
 * @fileoverview
 */
define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var $ = require('jquery');

    // api base url for ajax requests
    var BASE_URL = require('appConfig').apiBase;

    /*
     * Matches path keys to ajax source.
     * TODO: update to point to WP endpoints
     */
    var PATHS = {
        'mission': '/pages/mission.html',
        'approach': '/pages/approach.html',
        'approach/tertiary': '/pages/approach-tertiary.html',
        'impact': '/pages/impact.html',
        'press': '/pages/press-content.html',
        'contact': '/pages/contact-content.html',
        'pressResults': '/pages/press-content-addl.html',
        'searchResults': '/pages/search-results.html',
        '404': '/pages/not-found.html'
    };

    var PREFIX_STRIPPER = /^\//;

    var APIService = {

        /**
         * Returns html for a given basic route
         * @param {String} route Route name
         * @returns {Promise} represents value of html returned
         */
        getPanelContent: function(route) {
            route = route.replace(PREFIX_STRIPPER, '');
            var path = PATHS[route] || PATHS['404'];
            return Promise.resolve($.get(BASE_URL + path));
        },

        /**
         * Returns html for search results
         * @param {String} searchText Text from search box
         * @returns {Promise} represents value of search result html returned
         */
        getSearchResults: function(searchText) {
            return Promise.resolve($.get(BASE_URL + PATHS.searchResults));
        },

        /**
         * Returns html for lists of content
         * @param {String} contentType Content type
         * @param {Number} page Page Number
         * @returns {Promise} represents value of html returned
         */
        getMoreContent: function(contentType, page) {
            var resultsPath = PATHS[contentType + 'Results'];
            return Promise.resolve($.get(BASE_URL + resultsPath, {
                page: page
            }));
        }

    };

    return APIService;

});
