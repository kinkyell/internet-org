define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var $ = require('jquery');

    var AbstractView = require('./AbstractView');

    /**
     * Adds placeholder markup for images
     *
     * @class ImagePlaceholderView
     * @param {jQuery} $element A reference to the containing DOM element.
     * @constructor
     */
    var ImagePlaceholderView = function($element) {
        this._handleImageLoad = this._onImageLoad.bind(this);
        AbstractView.call(this, $element);
    };

    var proto = AbstractView.createChild(ImagePlaceholderView);

    /**
     * Performs any event binding to handlers.
     *
     * @method onEnable
     * @returns {MenuView}
     * @public
     */
    proto.onEnable = function() {
        this._setupPlaceholder();

        if (this.element.complete) {
            return;
        }

        this.$element.on('load', this._handleImageLoad);
    };

    /**
     * Tears down any event binding to handlers.
     *
     * @method onDisable
     * @returns {MenuView}
     * @public
     */
    proto.onDisable = function() {
        this.$element.off('load', this._handleImageLoad);
    };

    /**
     * Set up placeholder wrapper with set height
     *
     * @method _setupPlaceholder
     * @private
     */
    proto._setupPlaceholder = function() {
        var width = parseInt(this.element.getAttribute('width'), 10) || 16;
        var height = parseInt(this.element.getAttribute('height'), 10) || 9;
        var parent = this.element.parentNode;
        var imgLoaded = this.element.complete;

        // create placeholder wrapper
        var wrap = document.createElement('div');

        if (!imgLoaded) {
            wrap.style.paddingBottom = (height / width * 100) + '%';
            wrap.className = 'imgWrap isLoading';
        } else {
            wrap.className = 'imgWrap isLoaded';
        }

        // wrap img in div
        parent.insertBefore(wrap, this.element);
        wrap.appendChild(this.element);

        this.placeholder = wrap;
    };

    /**
     * Handle load event and remove loading styles
     *
     * @method _onImageLoad
     * @param {LoadEvent} event Image load event
     * @private
     */
    proto._onImageLoad = function(event) {
        $(this.placeholder)
            .removeClass('isLoading')
            .addClass('isLoaded')
            .removeAttr('style');
    };

    module.exports = ImagePlaceholderView;

});
