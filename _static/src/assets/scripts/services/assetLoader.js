define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var eventHub = require('services/eventHub');

    /**
     * Manages the stack of active states
     *
     * @class AssetLoader
     * @constructor
     */
    var AssetLoader = function() {
    };

    /**
     * Preload a single image
     *
     * @method loadImage
     * @param {String} url Url of image
     * @returns {Promise} promise representing image load state
     */
    AssetLoader.prototype.loadImage = function(url) {
        return new Promise(function(resolve, reject) {
            var img = new Image();

            img.onload = function() {
                resolve({
                    url: url,
                    img: img
                });
            };

            img.onerror = function(error) {
                reject({
                    url: url,
                    error: error
                });
            };

            img.src = url;

            // if already complete
            if (img.complete) {
                resolve({
                    url: url,
                    img: img
                });
            }
        });
    };

    /**
     * Preload an array of images
     *
     * @method loadImages
     * @param {Array.<String>} urls Array of image urls to load
     * @returns {Promise} promise representing image load state
     */
    AssetLoader.prototype.loadImages = function(urls) {
        var promises = urls.map(this.loadImage);
        return Promise.all(promises);
    };

    return new AssetLoader();

});
