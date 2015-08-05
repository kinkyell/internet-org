define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    /**
     * Manages loading of remote assets
     *
     * @class AssetLoader
     * @constructor
     */
    var AssetLoader = function() {
        /**
         * Array of already loaded image paths
         * @property _loaded
         * @default []
         * @type {Array}
         */
        this._loaded = [];
    };

    /**
     * Preload a single image
     *
     * @method loadImage
     * @param {String} url Url of image
     * @returns {Promise} promise representing image load state
     */
    AssetLoader.prototype.loadImage = function(url) {
        var loaded = this._loaded;

        if (loaded.indexOf(url) !== -1) {
            return Promise.resolve({ url: url });
        }
        return new Promise(function(resolve, reject) {
            var img = new Image();

            img.onload = function() {
                loaded.push(url);
                resolve({ url: url });
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
                loaded.push(url);
                resolve({ url: url });
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
        var promises = urls.map(this.loadImage, this);
        return Promise.all(promises);
    };

    return new AssetLoader();

});
