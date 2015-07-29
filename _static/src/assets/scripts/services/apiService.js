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
    }

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
                }).fail(function(error) {
                    reject(error);
                });
            });
        },

        /**
         * Returns example html for a given basic route
         */
        getPanelContent: function(route) {
            return new Promise(function(resolve, reject) {
                var path = PATHS[route] || PATHS['404'];
                $.get(BASE_URL + path).done(function(data) {
                    resolve(data);
                }).fail(function(error) {
                    reject(error);
                });
            });
        }

    };

    return APIService;

});
