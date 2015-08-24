/**
 * A service for handling API requests
 *
 * @fileoverview
 */
define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var $ = require('jquery');
    var templates = require('templates'); // jshint ignore:line

    // api base url for ajax requests
    var BASE_URL = require('appConfig').apiBase;

    /*
     * Matches path keys to ajax source.
     * TODO: update to point to WP endpoints
     */
    var PATHS = {
        'mission': 'mission/index.html',
        'approach': 'approach/index.html',
        'approach/tertiary': 'approach/tertiary/index.html',
        'impact': 'impact/index.html',
        'press': 'press/index.html',
        'contact': 'contact/index.html',
        'pressResults': 'pages/press-content-addl.html',
        'searchResults': 'search/data.html',
        '404': 'not-found/index.html'
    };

    var PREFIX_STRIPPER = /^\//;

    function _parseHtmlResponse(htmlStr) {
        var parsed = $.parseHTML(htmlStr);
        var viewWindowStory = parsed.reduce(function(found, element) {
            if (element.nodeName === 'DIV' && element.className.indexOf('viewWindow') !== -1) {
                return element.lastElementChild.firstElementChild.firstElementChild;
            }
            return found;
        }, null);

        if (viewWindowStory === null) {
            // this means it had no wrapper, send all html
            viewWindowStory = htmlStr;
        }

        return viewWindowStory;
    }

    var apiService = {


        /**
         * Returns html for a given basic route
         * @param {String} route Route name
         * @returns {Promise} represents value of html returned
         */
        getPanelContent: function(route) {
            route = route.replace(PREFIX_STRIPPER, '');
            var path = PATHS[route] || PATHS['404'];
            return Promise.resolve($.get(BASE_URL + path)).then(_parseHtmlResponse);
        },

        /**
         * Returns html for search results
         * @param {String} searchText Text from search box
         * @returns {Promise} represents value of search result html returned
         */
        getSearchResults: function(searchText, page) {
            page = page || 1;
            return Promise.resolve($.get(BASE_URL + PATHS.searchResults, {
                page: page
            }, 'json')).then(function(res) {
                if (typeof res === 'string') {
                    res = JSON.parse(res);
                }
                // jshint ignore:start
                return {
                    hasNextPage: res.next_page !== 0,
                    totalResults: typeof res.total_results === 'Number' ? res.total_results : 'Unknown',
                    results: res.results.map(function(result) {
                        return templates['search-result']({
                            title: result.title,
                            desc: result.excerpt,
                            url: result.permalink,
                            imgUrl: result.img_url,
                            formattedDate: result.date
                        });
                    }).join('')
                };
                // jshint ignore:end
            });
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
            })).then(_parseHtmlResponse);
        }

    };

    return apiService;

});
