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
        getExampleData: function() {
            return new Promise(function(resolve, reject) {
                $.get(BASE_URL + '/some/data').done(function(data) {
                    resolve(data);
                }).fail(function(error) {
                    reject(error);
                });
            });
        }

    };

    return APIService;

});
