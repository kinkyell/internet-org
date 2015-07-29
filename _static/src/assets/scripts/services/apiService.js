/**
 * A service for handling API requests
 *
 * @fileoverview
 */
define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var $ = require('jquery');

    var BASE_URL = require('appConfig').apiBase;

    var PATHS = {
        '/mission': '/pages/mission.html',
        '/approach': '/pages/approach.html',
        '/impact': '/pages/impact.html',
        '404': '/pages/not-found.html'
    };

    var APIService = {

        //TODO: remove example
        /**
         * Returns example html for demo
         */
        getExamplePage: function() {
            return new Promise(function(resolve, reject) {
                $.get(BASE_URL + '/pages/example-content.html').done(function(data) {
                    // simulate network requests
                    setTimeout(function() {
                        resolve(data);
                    }, 1000);
                }).fail(reject);
            });
        },

        /**
         * Returns html for a given basic route
         */
        getPanelContent: function(route) {
            return new Promise(function(resolve, reject) {
                var path = PATHS[route] || PATHS['404'];
                $.get(BASE_URL + path).done(resolve).fail(reject);
            });
        }

    };

    return APIService;

});
