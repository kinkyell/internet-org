define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var AbstractView = require('./AbstractView');
    var apiService = require('services/apiService');
    var tweenAsync = require('util/tweenAsync');
    var animationSpeeds = require('appConfig').animationSpeeds;

    /**
     * A view for displaying main menu
     *
     * @class ShowMoreView
     * @param {jQuery} $element A reference to the containing DOM element.
     * @constructor
     */
    var ShowMoreView = function($element) {
        AbstractView.call(this, $element);
    };

    var proto = AbstractView.createChild(ShowMoreView);

    /**
     * Binds the scope of any handler functions.
     * Should only be run on initialization of the view.
     *
     * @method setupHandlers
     * @returns {ShowMoreView}
     * @private
     */
    proto.setupHandlers = function() {
        this._handleTriggerClick = this._onTriggerClick.bind(this);
        this._handleContentLoad = this._onContentLoad.bind(this);
        this.enableButton = this.enableButton.bind(this);
        this.disableButton = this.disableButton.bind(this);
    };

    /**
     * Create any child objects or references to DOM elements.
     * Should only be run on initialization of the view.
     *
     * @method createChildren
     * @returns {ShowMoreView}
     * @private
     */
    proto.createChildren = function() {
        var targetId = this.element.getAttribute('data-target');
        this.targetEl = document.getElementById(targetId);
        this.contentType = this.element.getAttribute('data-src');
        this.nextPage = 1;
    };

    /**
     * Remove any child objects or references to DOM elements.
     *
     * @method removeChildren
     * @returns {ShowMoreView}
     * @public
     */
    proto.removeChildren = function() {
        this.targetEl = null;
    };

    /**
     * Performs any event binding to handlers.
     *
     * @method onEnable
     * @returns {ShowMoreView}
     * @public
     */
    proto.onEnable = function() {
        this.$element.on('click', this._handleTriggerClick);
    };

    /**
     * Tears down any event binding to handlers.
     *
     * @method onDisable
     * @returns {ShowMoreView}
     * @public
     */
    proto.onDisable = function() {
        this.$element.off('click', this._handleTriggerClick);
    };

    //////////////////////////////////////////////////////////////////////////////////
    // EVENT HANDLERS
    //////////////////////////////////////////////////////////////////////////////////

    /**
     * Toggle search menu on click
     *
     * @method _onTriggerClick
     * @param {ClickEvent} event Click event
     * @private
     */
    proto._onTriggerClick = function(event) {
        event.preventDefault();
        this.disableButton();
        this._loadAddlContent()
            .then(this._handleContentLoad)
            .then(this.enableButton);
    };

    /**
     * Toggle search menu on click
     *
     * @method _onContentLoad
     * @param {ClickEvent} event Click event
     * @private
     */
    proto._onContentLoad = function(html) {
        var newContent = document.createElement('div');
        newContent.innerHTML = html;
        this.targetEl.appendChild(newContent)

        return tweenAsync.from(newContent, animationSpeeds.ADDL_CONTENT, {
            height: 0,
            opacity: 0
        });
    };


    //////////////////////////////////////////////////////////////////////////////////
    // HELPERS
    //////////////////////////////////////////////////////////////////////////////////



    /**
     * Toggle search menu on click
     *
     * @method _loadAddlContent
     * @param {ClickEvent} event Click event
     * @private
     */
    proto._loadAddlContent = function() {
        var currentPage = this.nextPage;
        this.nextPage++;
        return apiService.getMoreContent(this.contentType, currentPage);
    };

    /**
     * Toggle search menu on click
     *
     * @method enableButton
     * @param {ClickEvent} event Click event
     * @private
     */
    proto.enableButton = function(content) {
        this.$element.removeClass('isLoading').removeAttr('disabled');
    };

    /**
     * Toggle search menu on click
     *
     * @method disableButton
     * @param {ClickEvent} event Click event
     * @private
     */
    proto.disableButton = function(content) {
        this.$element.addClass('isLoading').attr('disabled', '');
    };


    module.exports = ShowMoreView;

});
