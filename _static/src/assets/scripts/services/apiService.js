/**
 * A service for handling API requests
 *
 * @fileoverview
 */
define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var $ = require('jquery');

    var BASE_URL = require('appConfig').apiBase;

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
                    }, 1500);
                }).fail(function(error) {
                    reject(error);
                });
            });
        }

    };

    return APIService;

});
