/**
 * A service for handling API requests
 *
 * @fileoverview
 */
define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var $ = require('jquery');
    var templates = require('templates'); // jshint ignore:line
    var appConfig = require('appConfig');
    var eventHub = require('services/eventHub');

    /*
     * Matches path keys to ajax source.
     * TODO: update to point to WP endpoints
     */
    var PATHS = appConfig.apiRoutes;

    var PREFIX_STRIPPER = /^\//;
    var SUFFIX_STRIPPER = /\/$/;

    var $homepageEl = $('.js-stateHome');

    function _updateTitle(parsed) {
        parsed.forEach(function(element) {
            if (element.nodeName === 'TITLE') {
                document.title = element.firstChild.nodeValue;
            }
        });
        eventHub.publish('viewWindow:pageLoad', parsed);
    }

    function _getViewWindow(parsed) {
        var viewWindowEl = parsed.reduce(function(found, element) {
            if (element.nodeName === 'DIV' && element.className.indexOf('viewWindow') > -1) {
                if (element.id === 'brim-main') {
                    return element.querySelector('.js-viewWindow');
                }
                return element;
            }
            return found;
        }, null);
        return viewWindowEl;
    }

    function _parseHtmlResponse(htmlStr) {
        if (typeof htmlStr !== 'string') {
            htmlStr = htmlStr.responseText;
        }
        var parsed = $.parseHTML(htmlStr);
        var viewWindowEl = _getViewWindow(parsed);
        var viewWindowStory = viewWindowEl ? viewWindowEl.lastElementChild.firstElementChild.firstElementChild : null;
        viewWindowStory.className = '';

        _updateTitle(parsed);

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
            route = route.replace(PREFIX_STRIPPER, '').replace(SUFFIX_STRIPPER, '');
            var path = PATHS[route] || route;
            if (path.indexOf('http') !== 0) {
                path = '/' + path;
            }
            return Promise.resolve($.get(path)).then(_parseHtmlResponse, _parseHtmlResponse);
        },

        /**
         * Returns html for search results
         * @param {String} searchText Text from search box
         * @returns {Promise} represents value of search result html returned
         */
        getSearchResults: function(searchText, page) {
            page = page || 1;

            var path = '/' + appConfig.searchPath + encodeURIComponent(searchText) + '/page/' + page;

            var handleResponse = function(res) {
                if (typeof res === 'string') {
                    res = JSON.parse(res);
                }

                if (typeof res.success !== 'boolean') {
                    // catch http error
                    res = res.responseJSON;
                }

                if (!res.success) {
                    return {
                        hasNextPage: false,
                        totalResults: 0,
                        results: ''
                    };
                }

                // jshint ignore:start
                return {
                    hasNextPage: res.data.paged < res.data.max_num_pages,
                    totalResults: typeof res.data.found_posts === 'number' ? res.data.found_posts : 'Unknown',
                    results: res.data.posts.map(function(result) {
                        return templates['search-result']({
                            title: result.post_title,
                            desc: result.post_excerpt,
                            url: result.permalink,
                            type: result.post_type,
                            date: result.post_date,
                            image: result.panel_image,
                            mobileImage: result.mobile_image,
                            isPost: result.post_type === 'post',
                            isStory: result.post_type === 'io_story',
                            readMoreText: window.SETTINGS.READ_MORE_TEXT
                        });
                    }).join('')
                };
                // jshint ignore:end
            };

            return Promise.resolve($.get(path, {}, 'json')).then(handleResponse, handleResponse);
        },

        /**
         * Returns html for lists of content
         * @param {String} contentType Content type
         * @param {Number} page Page Number
         * @returns {Promise} represents value of html returned
         */
        getMoreContent: function(contentType, page, args, yearFilter) {
            var resultsPath = PATHS[contentType + 'Results'];
            var filterSection = yearFilter ? 'year/' + yearFilter + '/' : '';

            if (contentType === 'search') {
                return apiService.getSearchResults(args, page);
            }
            return Promise.resolve($.get('/' + resultsPath + filterSection + 'page/' + page)).then(function(res) {
                if (typeof res === 'string') {
                    res = JSON.parse(res);
                }

                if (!res.success) {
                    return {
                        hasNextPage: false,
                        totalResults: 0,
                        results: ''
                    };
                }

                // jshint ignore:start
                return {
                    hasNextPage: page < res.data.max_num_pages,
                    totalResults: typeof res.data.found_posts === 'number' ? res.data.found_posts : 'Unknown',
                    results: res.data.posts.map(function(result) {
                        return templates['archive-result']({
                            title: result.post_title,
                            desc: result.post_excerpt,
                            date: result.post_date,
                            url: result.permalink,
                            image: result.post_thumbnail,
                            readMoreText: window.SETTINGS.READ_MORE_TEXT
                        });
                    }).join('')
                };
                // jshint ignore:end
            });
        },

        /**
         * Returns html for homepage content
         * @returns {Promise} represents value of html returned
         */
        getHomepageContent: function() {
            var homepagePath = $homepageEl.length ? $homepageEl[0].href : '/';
            return Promise.resolve($.get(homepagePath)).then(function(html) {
                var parsed = $.parseHTML(html);
                var viewWindowEl = _getViewWindow(parsed);
                var homeEl = viewWindowEl.firstElementChild.firstElementChild.firstElementChild;
                var imageUrl;

                return {
                    el: homeEl,
                    image: imageUrl
                };
            });
        }

    };

    return apiService;

});
